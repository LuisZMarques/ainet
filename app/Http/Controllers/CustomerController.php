<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
        if (Auth::user()->isAdmin() || Auth::user()->id == $customer->id) {
            $customer->update($request->all());
            return redirect()->route('customers.index')
                ->with('alert-msg', 'Cliente atualizado com sucesso!')
                ->with('alert-type', 'success');
        } else {
            return redirect()->route('home')
                ->with('alert-msg', 'Não tem permissões para atualizar clientes!')
                ->with('alert-type', 'danger');
        }
    }

    public function destroy(Customer $customer): RedirectResponse
    {
        if (Auth::user()->isAdmin()) {
            DB::beginTransaction();
            try {
                $id = $customer->id;
                $customer_name = $customer->user->name;
                DB::table('order_items')
                    ->whereExists(function ($query) use ($id) {
                        $query->select(DB::raw(1))
                            ->from('orders')
                            ->whereRaw('order_items.order_id = orders.id')
                            ->where('orders.customer_id', '=', $id);
                    })->delete();

                DB::table('orders')
                    ->where('customer_id', '=', $id)
                    ->delete();

                DB::table('tshirt_images')
                    ->where('customer_id', '=', $id)
                    ->delete();

                DB::table('customers')
                    ->where('id', '=', $id)
                    ->delete();

                DB::table('users')
                    ->where('id', '=', $id)
                    ->delete();
            
                DB::commit();
                $htmlMessage = "Cliente <strong>\"{$customer_name}\"</strong> apagado com sucesso!";
                $alertType = 'success';

                return redirect()->route('customers.index')
                    ->with('alert-msg', "Customer #{$id} apagado com sucesso!")
                    ->with('alert-type', 'success');

            } catch (\Exception $error) {
                DB::rollback();
                $url = route('customers.index');
                $htmlMessage = "Não foi possível apagar o cliente <strong>\"{$customer_name}\"</strong>! <a href=\"{$url}\" class=\"alert-link\">Ver clientes</a>";
                $alertType = 'danger';
            }
            return back()
                ->with('alert-msg', $htmlMessage)
                ->with('alert-type', $alertType);
        } else {
            return redirect()->route('home')
                ->with('alert-msg', 'Não tem permissões para apagar clientes!')
                ->with('alert-type', 'danger');
        }
    }
}
