<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    public function index(Request $request) : View
    {   
        if (Auth::user()->isAdmin()) {
            $query = Customer::with('user');
            $search = $request->input('search');
            if ($search) {
                $query->whereHas('user', function ($q) use ($search) {
                    $q->where('name', 'LIKE', '%' . $search . '%');
                });
            }
            $customers = $query->paginate(15);

            return view('customers.index', compact('customers'));
        } else {
            return view('home')->with('alert-msg', 'Não tem permissões para ver clientes!')->with('alert-type', 'danger');
        }
    }

    public function create(): View
    {
        if(Auth::user()->isAdmin()) {
            $customer = new Customer();
            return view('customers.create', compact('customer'));
        } else {
            return view('home')->with('alert-msg', 'Não tem permissões para criar clientes!')->with('alert-type', 'danger');
        }
    }

    public function show(Customer $customer) : View
    {
        if (Auth::user()->isAdmin() || Auth::user()->id == $customer->id) {
            return view('customers.show', compact('customer'));
        } else {
            return view('home')->with('alert-msg', 'Não tem permissões para ver clientes!')->with('alert-type', 'danger');
        }
    }

    public function edit(Customer $customer): View
    {
        if (Auth::user()->isAdmin() || Auth::user()->id == $customer->id) {
            return view('customers.edit', compact('customer'));
        } else {
            return view('home')->with('alert-msg', 'Não tem permissões para ver clientes!')->with('alert-type', 'danger');
        }
    }

    public function update(Request $request, Customer $customer): RedirectResponse
    {
        $customer->update($request->all());
        return redirect('/clientes');
    }

    public function store(Request $request): RedirectResponse
    {
        Customer::create($request->all());
        return redirect('/clientes');
    }

    public function destroy(Customer $customer): RedirectResponse
    {
        #$customer->orders()->delete();
        $customer->delete();
        $customer->user()->delete();
        return redirect('/clientes');
    }
}
