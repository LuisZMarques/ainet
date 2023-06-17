<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use App\Models\OrderItem;
use App\Models\TshirtImage;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class CartController extends Controller
{
    public function __construct()
    {
        // Apply the middlware 'can:access-cart' to all methods of the controlelr
        $this->middleware('can:access-cart');
    }
 
    public function show(): View
    {
        $cart = session('cart', []);
        return view('cart.show', compact('cart'));
    }

    public function addToCart(Request $request): RedirectResponse
    {
        // Replaced with the gate 'access-cart' (middleware applied on the contructor)
        try {
            $cart = session('cart', []);
            $tshirtImageId = $request->input('idImage');
            $tshirtImageName = $request->input('nameImage');
            $tshirtSize = $request->input('size');
            $tshirtColor = $request->input('color');
            $tshirtQuantity = $request->input('quantity');
            $tshirtUnitPrice = $request->input('unitPrice');
            $tshirtSubTotal = $request->input('subTotal');
            $tshirtUniqueId = $tshirtImageId . $tshirtSize . $tshirtColor;

            if (array_key_exists($tshirtUniqueId, $cart)) {
                $url = route('cart.show');
                $alertType = 'warning';
                $htmlMessage = "Este pedido já tem tshirts com a imagem ' <strong>\"{$tshirtImageName}\"</strong> ', cor ' <strong>\"{$tshirtColor}\"</strong> ' e tamanho ' <strong>\"{$tshirtSize}\"</strong> ' adicionada ao carrinho!";
            } else {
                $cart[$tshirtUniqueId] = [
                    'imageId' => $tshirtImageId,
                    'size' => $tshirtSize,
                    'color' => $tshirtColor,
                    'quantity' => $tshirtQuantity,
                    'unitPrice' => $tshirtUnitPrice,
                    'subTotal' => $tshirtSubTotal,
                ];
                
                if (empty($cart[0])) {
                    $cart[0] = [];
                }
                
                array_push($cart[0], $tshirtUniqueId);

                $request->session()->put('cart', $cart);
                $alertType = 'success';
                $url = route('cart.show');
                $htmlMessage = "Tshirt com imagem ' <strong>\"{$tshirtImageName}\"</strong> ', cor ' <strong>\"{$tshirtColor}\"</strong> ' e tamanho ' <strong>\"{$tshirtSize}\"</strong> ' adicionada ao carrinho! <a href='$url'>Ver carrinho</a>";
            }
        } catch (\Exception $error) {
            $url = route('cart.show');
            $htmlMessage = "Não é possível adicionar a tshirt com a imagem ' <strong>\"{$tshirtImageName}\"</strong> ' ao carrinho, porque ocorreu um erro! /n \n $error";
            $alertType = 'danger';
        }
        return back()
            ->with('alert-msg', $htmlMessage)
            ->with('alert-type', $alertType);
    }

    public function removeFromCart(Request $request, OrderItem $tshirt): RedirectResponse
    {
        $cart = session('cart', []);
        if (array_key_exists($tshirt->id, $cart)) {
            unset($cart[$tshirt->id]);
        }
        $request->session()->put('cart', $cart);
        $url = route('cart.show');
        $htmlMessage = "tshirt <a href='$url'>#{$tshirt->id}</a>
                        <strong>\"{$tshirt->nome}\"</strong> foi removida do carrinho!";
        return back()
            ->with('alert-msg', $htmlMessage)
            ->with('alert-type', 'success');
    }

    public function store(Request $request): RedirectResponse
    {
        try {
            $cart = session('cart', []);
            $total = count($cart);
            if ($total < 1) {
                $alertType = 'warning';
                $htmlMessage = "Não é possível confirmar as inscrições porque não há disciplina no carrinho";
            } else {
                $aluno = $request->user()->aluno;
                DB::transaction(function () use ($aluno, $cart) {
                    foreach ($cart as $disciplina) {
                        $aluno->disciplinas()->attach($disciplina->id, ['repetente' => 0]);
                    }
                });
                if ($total == 1) {
                    $htmlMessage = "Foi confirmada a inscrição a 1 disciplina ao aluno #{$aluno->id} <strong>\"{$request->user()->name}\"</strong>";
                } else {
                    $htmlMessage = "Foi confirmada a inscrição a $total disciplinas ao aluno #{$aluno->id} <strong>\"{$request->user()->name}\"</strong>";
                }
                $request->session()->forget('cart');
                return redirect()->route('disciplinas.minhas')
                    ->with('alert-msg', $htmlMessage)
                    ->with('alert-type', 'success');
            }
            
        } catch (\Exception $error) {
            $htmlMessage = "Não foi possível inserir as tshirts no carrinho, porque ocorreu um erro!";
            $alertType = 'danger';
        }
        return back()
            ->with('alert-msg', $htmlMessage)
            ->with('alert-type', $alertType);
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->session()->forget('cart');
        $htmlMessage = "Carrinho está limpo!";
        return back()
            ->with('alert-msg', $htmlMessage)
            ->with('alert-type', 'success');
    }
}
