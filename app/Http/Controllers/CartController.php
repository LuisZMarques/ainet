<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use App\Models\OrderItem;
use App\Models\User;
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

    public function addToCart(Request $request, OrderItem $tshirt): RedirectResponse
    {
        // Replaced with the gate 'access-cart' (middleware applied on the contructor)
        try {
            if ($request->user()->isCustomer() !== true) {
                $alertType = 'warning';
                $htmlMessage = "Não é Cliente, logo não pode adicionar tshirts ao carrinho";
            } else {
                $cart[$tshirt->id] = $tshirt;
                // We can access session with a request function
                $request->session()->put('cart', $cart);
                $alertType = 'success';
                $url = route('orderItem.show', ['tshirt' => $tshirt]);
                $htmlMessage = "tshirt <a href='$url'>#{$tshirt->id}</a>
                <strong>\"{$tshirt->tshirt_images()->nome}\"</strong> foi adicionada ao carrinho!";
            }
        } catch (\Exception $error) {
            $url = route('OrderItem.show', ['tshirt' => $tshirt]);
            $htmlMessage = "Não é possível adicionar a tshirt <a href='$url'>#{$tshirt->id}</a>
                        <strong>\"{$tshirt->nome}\"</strong> ao carrinho, porque ocorreu um erro!";
            $alertType = 'danger';
        }
        return back()
            ->with('alert-msg', $htmlMessage)
            ->with('alert-type', $alertType);
    }

    public function removeFromCart(Request $request, Disciplina $disciplina): RedirectResponse
    {
        $cart = session('cart', []);
        if (array_key_exists($disciplina->id, $cart)) {
            unset($cart[$disciplina->id]);
        }
        $request->session()->put('cart', $cart);
        $url = route('disciplinas.show', ['disciplina' => $disciplina]);
        $htmlMessage = "Disciplina <a href='$url'>#{$disciplina->id}</a>
                        <strong>\"{$disciplina->nome}\"</strong> foi removida do carrinho!";
        return back()
            ->with('alert-msg', $htmlMessage)
            ->with('alert-type', 'success');
    }

    public function store(Request $request): RedirectResponse
    {
        try {
            $userType = $request->user()->tipo ?? 'O';
            if ($userType != 'A') {
                $alertType = 'warning';
                $htmlMessage = "O utilizador não é aluno, logo não pode confirmar as inscrições às disciplina do carrinho";
            } else {
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
            }
        } catch (\Exception $error) {
            $htmlMessage = "Não foi possível confirmar a inscrição das disciplinas do carrinho, porque ocorreu um erro!";
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
