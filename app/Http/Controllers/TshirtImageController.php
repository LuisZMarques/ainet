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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Pagination\Paginator;
 

class TshirtImageController extends Controller
{
    public function index(Request $request) : View
    {
        if (Auth::user()->isAdmin()) {
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
        } else {
            return view('home')->with('alert-msg', 'Não tem permissões para ver a lista de imagens das t-shirts!')->with('alert-type', 'danger');
        }
    }

    public function show(TshirtImage $tshirtImage): View
    {
        if (Auth::user()->isAdmin() || Auth::user()->id == $tshirtImage->customer_id) {
            $categories = Category::all();
            $customer = Customer::where('id', $tshirtImage->customer_id)->first();
            return view('tshirt_images.show', compact('tshirtImage', 'categories', 'customer'));
        } else {
            return view('home')->with('alert-msg', 'Não tem permissões para ver as imagens das t-shirts!')->with('alert-type', 'danger');
        }
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

            $tshirtImages = $query->paginate(15);
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

            $tshirtImages = $query->paginate(15);
            $categories = Category::all();
            $colors = Color::all();
            $prices = Price::first();
            return view('tshirt_images.catalogo', compact('tshirtImages', 'categories', 'colors', 'prices'));
        }
        return view('home')->with('alert-msg', 'Não tem permissões para ver o catálogo de imagens das t-shirts!')->with('alert-type', 'danger');
    }

    public function create(): View
    {   
        if(Auth::user()->isAdmin() || Auth::user()->isCustomer()){
            $tshirtImage = new TshirtImage();
            $categories = Category::all();
            return view('tshirt_images.create' , compact('categories', 'tshirtImage'));
        }else{
            return view('home')->with('alert-msg', 'Não tem permissões para criar imagens de tshirts!')->with('alert-type', 'danger');
        }
    }


    public function edit(TshirtImage $tshirtImage): View
    {
        if(Auth::user()->isAdmin() || Auth::user()->id == $tshirtImage->customer_id){
            $categories = Category::all();
            $customer = Customer::where('id', $tshirtImage->customer_id)->first();
            return view('tshirt_images.edit', compact('tshirtImage', 'categories', 'customer'));
        }else{
            return view('home')->with('alert-msg', 'Não tem permissões para editar imagens de tshirts!')->with('alert-type', 'danger');
        }
    }

    public function update(Request $request, TshirtImage $tshirtImage): View
    {   
        if(Auth::user()->isAdmin() || Auth::user()->id == $tshirtImage->customer_id){
            $categories = Category::all();
            $tshirtImage->update($request->all());
            return view('tshirt_images.edit', compact('tshirtImage', 'categories'));
        }else{
            return view('home')->with('alert-msg', 'Não tem permissões para editar imagens de tshirts!')->with('alert-type', 'danger');
        }
    }

    public function minhasTshirtImages(Request $request): View
    {
        if(Auth::user()->isCustomer()) {
            $customer = $request->user()->customer;
            $tshirtImages = $request->user()->customer->tshirt_images;
        
            return view('tshirt_images.minhas', compact('tshirtImages', 'customer'));
        }else{
            return view('home')->with('alert-msg', 'Não é cliente, logo não tem imagens de tshirts!')->with('alert-type', 'danger');
        }
    }

    public function store(Request $request): RedirectResponse
    {
        if(Auth::user()->isCustomer()){
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

    public function destroy(TshirtImage $tshirt_image): RedirectResponse
    {
        if(Auth::user()->isAdmin() || Auth::user()->id == $tshirt_image->customer_id)
            try {
                $tshirt_image->delete();
                if ($tshirt_image->image_url) {
                    Storage::delete('/storage/tshirt_images/' . $tshirt_image->image_url);
                }
                return redirect()->route('tshirt_images.index')
                    ->with('alert-msg', "Tshirt Image #{$tshirt_image->id} apagada com sucesso!")
                    ->with('alert-type', 'success');
            } catch (\Exception $error) {
                $url = route('tshirt_images.show', ['tshirt_image' => $tshirt_image]);
                $htmlMessage = "Não foi possível apagar a Tshirt Image <a href='$url'>#{$tshirt_image->id}</a> porque ocorreu um erro!" . $error->getMessage();
                $alertType = 'danger';
                return back()
                    ->with('alert-msg', $htmlMessage)
                    ->with('alert-type', $alertType);
        }else{
            return redirect()->route('home')->with('alert-msg', 'Não tem permissões para apagar imagens de tshirts!')->with('alert-type', 'danger');
        }
    }
}
