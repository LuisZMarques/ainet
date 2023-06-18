<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Color;
use App\Models\OrderItem;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;

class ColorController extends Controller
{
    public function index() : View
    {
        if (Auth::user()->isAdmin()) {
            $colors = Color::paginate(15);
            return view('colors.index', compact('colors'));
        } else {
            return view('home')->with('alert-msg', 'Não tem permissões para ver cores!')->with('alert-type', 'danger');
        }
    }

    public function create() : View
    {
        if (Auth::user()->isAdmin()) {
            $color = new Color();
            return view('colors.create', compact('color'));
        } else {
            return view('home')->with('alert-msg', 'Não tem permissões para criar cores!')->with('alert-type', 'danger');
        }
    }

    public function show(Color $color) : View
    {
        if (Auth::user()->isAdmin()) {
            return view('colors.show', compact('color'));
        } else {
            return view('home')->with('alert-msg', 'Não tem permissões para ver cores!')->with('alert-type', 'danger');
        }
    }

    public function edit(Color $color): View
    {
        if (Auth::user()->isAdmin()) {
            return view('colors.edit')->with('color', $color);
        } else {
            return view('home')->with('alert-msg', 'Não tem permissões para ver cores!')->with('alert-type', 'danger');
        }
    }

    public function update(Request $request, Color $color) : View
    {
        if (Auth::user()->isAdmin()) {
            $color->update($request->all());
            return view('colors.edit', compact('color'));
        } else {
            return view('home')->with('alert-msg', 'Não tem permissões para editar cores!')->with('alert-type', 'danger');
        }
    }

    public function store(Request $request): RedirectResponse
    {
        Color::create($request->all());
        return redirect()->route('colors.index');
    }

    public function destroy(Color $color): RedirectResponse
    {
        try {
            $isUsed = OrderItem::where('color_code', $color->code)->count();
            if ($isUsed > 0) {
                throw new \Exception('A cor está sendo usada numa tshirt ou mais.');
            }
    
            $color->delete();

            return redirect()->route('colors.index');

        } catch (\Exception $error) {
            $url = route('colors.index');
            $htmlMessage = "Não foi possível apagar a cor <a href='$url'>#{$color->id}</a> <strong>\"{$color->name}\"</strong> porque ocorreu um erro!" . $error->getMessage();
            $alertType = 'danger';
        }
        return back()
            ->with('alert-msg', $htmlMessage)
            ->with('alert-type', $alertType);
    }
    
}
