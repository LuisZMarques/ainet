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
use App\Http\Requests\CategoryRequest;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Category::class, 'category');
    }
    
    public function index(Request $request) : View
    {
        $query = Category::query();
        $search = $request->input('search');

        if ($search) {
            $query->where('name', 'LIKE', '%' . $search . '%');
        }

        $categories = $query->paginate(15);

        return view('categories.index', compact('categories'));
    }

    public function create(): View
    {
        $category = new Category();
        return view('categories.create', compact('category'));
    }

    public function show(Category $category) : View
    {
        return view('categories.show', compact('category'));
    }
    public function edit(Category $category): View
    {
        return view('categories.edit')->with('category', $category);
    }

    public function update(CategoryRequest $request, Category $category) : RedirectResponse
    {
        $category->update($request->validated());
        return redirect()->route('categories.index')
            ->with('alert-msg', "Categoria #{$category->id} \"{$category->name}\" atualizada com sucesso!")
            ->with('alert-type', 'success');
    }

    public function store(CategoryRequest $request): RedirectResponse
    {
        Category::create($request->validated());
        return redirect()->route('categories.index')
            ->with('alert-msg', 'Categoria criada com sucesso!')
            ->with('alert-type', 'success');
    }

    public function destroy(Category $category): RedirectResponse
    {
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
    }
}
