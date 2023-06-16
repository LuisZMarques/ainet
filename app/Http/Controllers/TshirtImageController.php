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
        if(Auth::user()->isCustomer() || Auth::user()->isAdmin()){
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
        }else{
            return view('home')->with('alert-msg', 'Não tem permissões para ver o catálogo de imagens das t-shirts!')->with('alert-type', 'danger');
        }
    }

    public function create(): View
    {   
        if(Auth::user()->isCustomer()){

                $categories = Category::all();
                $tshirtImage = new TshirtImage();
                $tshirtImage->category_id = null;
                $tshirtImage->customer_id = Auth::user()->id;
                $tshirtImage->image_url = "teste";

            return view('tshirt_images.create', compact('tshirtImage' ,'categories'));
        }elseif(Auth::user()->isAdmin()){

                $categories = Category::all();
                $tshirtImage = new TshirtImage();
                $tshirtImage->customer_id = null;
                $tshirtImage->image_url = "teste";
            return view('tshirt_images.create', compact('tshirtImage' ,'categories'));
        }else{
            return view('home')->with('alert-msg', 'Não tem permissões para criar imagens de tshirts!')->with('alert-type', 'danger');
        }
    }


    public function edit(TshirtImage $tshirtImage): View
    {
        if(Auth::user()->isAdmin()){
            $categories = Category::all();
            return view('tshirt_images.edit', compact('tshirtImage', 'categories'));
        }else{
            return view('home')->with('alert-msg', 'Não tem permissões para editar imagens de tshirts!')->with('alert-type', 'danger');
        }
    }

    public function update(Request $request, TshirtImage $tshirtImage): View
    {   
        if(Auth::user()->isAdmin()){
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
            $tshirtImages = $customer->tshirt_images;

            return view('tshirt_images.minhas', compact('tshirtImages', 'customer'));
        }else{
            return view('home')->with('alert-msg', 'Não é cliente, logo não tem imagens de tshirts!')->with('alert-type', 'danger');
        }
    }

    public function store(Request $request): RedirectResponse
    {
        TshirtImage::create($request->all());
        return redirect('/tshirt_images');
    }

    public function destroy(TshirtImage $tshirt_image): RedirectResponse
    {
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
        }
    }
}
