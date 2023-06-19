<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\TshirtImage;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    public function index(Request $request) : View
    {
        if (Auth::user()->isAdmin()) {
            $query = Category::query();
            $search = $request->input('search');

            if ($search) {
                $query->where('name', 'LIKE', '%' . $search . '%');
            }

            $categories = $query->paginate(15);

            return view('categories.index', compact('categories'));
        } else {
            return view('home')->with('alert-msg', 'Não tem permissões para ver categorias!')->with('alert-type', 'danger');
        }
    }

    public function create(): View
    {
        if (Auth::user()->isAdmin()) {
            $category = new Category();
            return view('categories.create', compact('category'));
        } else {
            return view('home')->with('alert-msg', 'Não tem permissões para criar categorias!')->with('alert-type', 'danger');
        }
    }

    public function show(Category $category) : View
    {
        if (Auth::user()->isAdmin()) {
            return view('categories.show', compact('category'));
        } else {
            return view('home')->with('alert-msg', 'Não tem permissões para ver categorias!')->with('alert-type', 'danger');
        }
    }
    public function edit(Category $category): View
    {
        if (Auth::user()->isAdmin()) {
            return view('categories.edit')->with('category', $category);
        } else {
            return view('home')->with('alert-msg', 'Não tem permissões para ver categorias!')->with('alert-type', 'danger');
        }
    }

    public function update(Request $request, Category $category) : View
    {
        if (Auth::user()->isAdmin()) {
            $category->update($request->all());
            return view('categories.show', compact('category'));
        } else {
            return view('home')->with('alert-msg', 'Não tem permissões para editar categorias!')->with('alert-type', 'danger');
        }
    }

    public function store(Request $request): RedirectResponse
    {
        if (Auth::user()->isAdmin()) {
            Category::create($request->all());
            return redirect()->route('categories.index')
                ->with('alert-msg', 'Categoria criada com sucesso!')
                ->with('alert-type', 'success');
        } else {
            return redirect()->route('home')
                ->with('alert-msg', 'Não tem permissões para criar categorias!')
                ->with('alert-type', 'danger');
        }
    }

    public function destroy(Category $category): RedirectResponse
    {
        if (Auth::user()->isAdmin()) {
            try {
                $images = DB::table('tshirt_images')->where('category_id', $category->id)->count();
                
                if ($images == 0) {
                    DB::transaction(function () use ($category) {
                        $category->delete();
                    });
                    
                    return redirect()->route('categories.index')
                        ->with('alert-msg', "Categoria #{$category->id} \"{$category->name}\" apagada com sucesso!")
                        ->with('alert-type', 'success');
                } else {
                    return redirect()->route('categories.index')
                        ->with('alert-msg', "Não foi possível apagar a categoria #{$category->id} \"{$category->name}\" porque existem imagens associadas a esta categoria!")
                        ->with('alert-type', 'danger');
                }
            } catch (\Exception $error) {
                $url = route('categories.index');
                $htmlMessage = "Não foi possível apagar a categoria <a href='$url'>#{$category->id}</a> <strong>\"{$category->name}\"</strong> porque ocorreu um erro!" . $error->getMessage();
                $alertType = 'danger';
            }
            return back()
                ->with('alert-msg', $htmlMessage)
                ->with('alert-type', $alertType);
        }else {
            return redirect()->route('home')
                ->with('alert-msg', 'Não tem permissões para apagar categorias!')
                ->with('alert-type', 'danger');
        }
    }
}
