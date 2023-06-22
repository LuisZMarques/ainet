<?php

namespace App\Http\Controllers;

use App\Models\Price;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\PriceRequest;

class PriceController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Price::class, 'price');
    }
    public function index() : View
    {
        $price = Price::all()->first();
        return view('prices.index', compact('price'));
    }

    public function edit(Price $price) : View
    {
        return view('prices.edit', compact('price'));
    }

    public function update(PriceRequest $request, Price $price) : RedirectResponse
    {
        $price->update($request->validated());
        return redirect()->route('prices.index')
            ->with('alert-msg', "Preço atualizado com sucesso!")
            ->with('alert-type', 'success');
    }
}
