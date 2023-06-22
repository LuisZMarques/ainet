<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TshirtImage;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Color;
use App\Models\Price;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\TshirtImageRequest;
 

class TshirtImageController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(TshirtImage::class, 'tshirt_image');
    }

    public function index(Request $request) : View
    {
        $query = TshirtImage::query()->whereNull('customer_id');
        $category = $request->input('category');
        $search = $request->input('search');

        if ($category) {
            $query->where('category_id', $category);
        }

        if ($search) {
            $query->where('name', 'LIKE', '%' . $search . '%');
        }

        $tshirtImages = $query->paginate(15);
        $categories = Category::all();

        return view('tshirt_images.index', compact('tshirtImages', 'categories'));
    }

    public function show(TshirtImage $tshirtImage): View
    {
        $categories = Category::all();
        $customer = Customer::where('id', $tshirtImage->customer_id)->first();
        return view('tshirt_images.show', compact('tshirtImage', 'categories', 'customer'));    
    }

    public function catalogo(Request $request) : View
    {
        if((Auth::check() && Auth::user()->isCustomer())){
            $customer_id = Auth::user()->id;
            
            $category = $request->input('category');
            $search = $request->input('search');

            $query = TshirtImage::query()
                ->where(function ($query) use ($customer_id) {
                    $query->whereNull('customer_id')
                        ->orWhere('customer_id', $customer_id);
                });

            if ($category) {
                $query->where('category_id', $category);
            }

            if ($search) {
                $query->where('name', 'LIKE', '%' . $search . '%');
            }

            $tshirtImages = $query->orderBy('created_at', 'desc')->paginate(15);
            $categories = Category::all();
            $colors = Color::all();
            $prices = Price::first();
            return view('tshirt_images.catalogo', compact('tshirtImages', 'categories', 'colors', 'prices'));

        }elseif(Auth::guest()){
            
            $category = $request->input('category');
            $search = $request->input('search');

            $query = TshirtImage::query()
                ->where(function ($query) {
                    $query->whereNull('customer_id');
                });

            if ($category) {
                $query->where('category_id', $category);
            }
            
            if ($search) {
                $query->where('name', 'LIKE', '%' . $search . '%');
            }

            $tshirtImages = $query->orderBy('created_at', 'desc')->paginate(15);
            $categories = Category::all();
            $colors = Color::all();
            $prices = Price::first();
            return view('tshirt_images.catalogo', compact('tshirtImages', 'categories', 'colors', 'prices'));
        }
        return view('home')->with('alert-msg', 'Não tem permissões para ver o catálogo de imagens das t-shirts!')->with('alert-type', 'danger');
    }

    public function create(): View
    {   
        $tshirtImage = new TshirtImage();
        $categories = Category::all();
        return view('tshirt_images.create' , compact('categories', 'tshirtImage'));
    }


    public function edit(TshirtImage $tshirtImage): View
    {
        $categories = Category::all();
        $customer = Customer::where('id', $tshirtImage->customer_id)->first();
        return view('tshirt_images.edit', compact('tshirtImage', 'categories', 'customer'));
    }

    public function update(TshirtImageRequest $request, TshirtImage $tshirtImage): View
    {   
        $categories = Category::all();
        $tshirtImage->update($request->validated());
        return view('tshirt_images.edit', compact('tshirtImage', 'categories'));
    }

    public function minhasTshirtImages(Request $request): View
    {
        $this->authorize('minhasTshirtImages', TshirtImage::class);
        $customer = $request->user()->customer;
        $tshirtImages = $request->user()->customer->tshirt_images;
        
    
        return view('tshirt_images.minhas', compact('tshirtImages', 'customer'));
    }

    public function store(TshirtImageRequest $request): RedirectResponse
    {
        if(Auth::user()->isCustomer()){
            $tshirtImageValidated = $request->validated();
            $tshirtImage = new TshirtImage();
            $tshirtImage->name = $request->input('name');
            $tshirtImage->description = $request->input('description');
            $tshirtImage->category_id = null;
            $tshirtImage->customer_id = $request->user()->id;
            $tshirtImage->image_url = "teste";
            $tshirtImage->save();

        return redirect()->route('tshirt_images.minhas')
            ->with('alert-msg', 'Imagem criada com sucesso!')
            ->with('alert-type', 'success');

        }elseif(Auth::user()->isAdmin()){

            $tshirtImageValidated = $request->validated();
            $tshirtImage = new TshirtImage();
            $tshirtImage->name = $request->input('name');
            $tshirtImage->description = $request->input('description');
            $tshirtImage->category_id = $request->input('category_id');
            $tshirtImage->customer_id = null;
            $tshirtImage->image_url = "teste";
            $tshirtImage->save();

            return redirect()->route('tshirt_images.index')
                ->with('alert-msg', 'Imagem criada com sucesso!')
                ->with('alert-type', 'success');
        }else{
            return redirect()->route('home')->with('alert-msg', 'Não tem permissões para criar imagens de tshirts!')->with('alert-type', 'danger');
        }
    }

    public function destroy(TshirtImage $tshirtImage): RedirectResponse
    {
        try {
            if ($tshirtImage->orderItems()->count() > 0) {
                $tshirtImage->orderItems()->delete();
            }
            $tshirtImage->delete();
            if ($tshirtImage->image_url) {
                Storage::delete('/storage/tshirt_images/' . $tshirtImage->image_url);
            }
            return redirect()->route('tshirt_images.index')
                ->with('alert-msg', "Tshirt Image #{$tshirtImage->id} apagada com sucesso!")
                ->with('alert-type', 'success');
        } catch (\Exception $error) {
            $url = route('tshirt_images.show', ['tshirt_image' => $tshirtImage]);
            $htmlMessage = "Não foi possível apagar a Tshirt Image <a href='$url'>#{$tshirtImage->id}</a> porque ocorreu um erro!" . $error->getMessage();
            $alertType = 'danger';
        }
        return back()
            ->with('alert-msg', $htmlMessage)
            ->with('alert-type', $alertType);
    }
}
