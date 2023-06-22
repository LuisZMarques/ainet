<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Color;
use App\Models\OrderItem;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\ColorRequest;

class ColorController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Color::class, 'color');
    }

    public function index() : View
    {
        $colors = Color::paginate(15);
        return view('colors.index', compact('colors'));
    }

    public function create() : View
    {
        $color = new Color();
        return view('colors.create', compact('color'));
    }

    public function show(Color $color) : View
    {
        return view('colors.show', compact('color'));
    }

    public function edit(Color $color): View
    {
        return view('colors.edit')->with('color', $color);
    }

    public function update(ColorRequest $request, Color $color) : RedirectResponse
    {
        $color->update($request->validated());
        return redirect()->route('colors.index')
            ->with('alert-msg', "Cor <strong>\"{$color->name}\"</strong> atualizada com sucesso!")
            ->with('alert-type', 'success');
    }

    public function store(ColorRequest $request): RedirectResponse
    {
        Color::create($request->validated());
        return redirect()->route('colors.index')
            ->with('alert-msg', "Cor <strong>\"{$request->name}\"</strong> criada com sucesso!")
            ->with('alert-type', 'success');
    }

    public function destroy(Color $color): RedirectResponse
    {
        try {
            $isUsed = OrderItem::where('color_code', $color->code)->count();
            if ($isUsed > 0) {
                throw new \Exception('A cor está sendo usada numa tshirt ou mais.');
            }
    
            $color->delete();

            return redirect()->route('colors.index')
                ->with('alert-msg', "Cor <strong>\"{$color->name}\"</strong> apagada com sucesso!")
                ->with('alert-type', 'success');

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
