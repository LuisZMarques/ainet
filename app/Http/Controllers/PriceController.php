<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Price;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class PriceController extends Controller
{
    public function index() : View
    {
        $price = Price::all()->first();
        return view('prices.index', compact('price'));
    }

    public function edit(Price $price) : View
    {
        return view('prices.edit', compact('price'));
    }

    public function update(Request $request, Price $price) : RedirectResponse
    {
        $price->update($request->all());
        return redirect()->route('prices.index')
            ->with('alert-msg', "Preço atualizado com sucesso!")
            ->with('alert-type', 'success');
    }
}
