<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index(Request $request) : View
    {
        $query = Order::query();

        // Filtro de estado
        $status = $request->input('status');
        if ($status) {
            $query->where('status', $status);
        }

        // Filtro de pesquisa por nome do cliente
        $search = $request->input('search');
        if ($search) {
            $query->whereHas('customer.user', function ($q) use ($search) {
                $q->where('name', 'LIKE', '%' . $search . '%');
            });
        }

        $orders = $query->paginate(15);

        return view('orders.index', compact('orders'));
    }
    

    public function create(): View
    {
        return view('orders.create');
    }

    public function show(Order $order): View
    {
        return view('orders.show', compact('order'));
    }

    public function edit(Order $order): View
    {
        return view('orders.edit', compact('order'));
    }

    public function minhasEncomendas(Request $request): View
    {
        if(Auth::user()->isCustomer()) {
            $orders = $request->user()->customer->orders;
            return view('orders.minhas', compact('orders'));
        }else{
            return view('home')->with('alert-msg', 'Como é cliente não tem acesso a encomendas.')->with('alert-type', 'danger');
        }
    }

    public function update(Request $request, Order $order): RedirectResponse
    {
        $order->update($request->all());
        return redirect()->route('orders.index');
    }

    public function store(Request $request): RedirectResponse
    {
        Order::create($request->all());
        return redirect()->route('orders.index');
    }

    public function destroy(Order $order): RedirectResponse
    {
        $order->delete();
        return redirect()->route('orders.index');
    }

}