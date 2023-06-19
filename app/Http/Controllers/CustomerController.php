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
    public function __construct()
    {
        $this->authorizeResource(Customer::class, 'customer');
    }

    public function index(Request $request) : View
    {   
        $query = Customer::with('user');
        $search = $request->input('search');
        if ($search) {
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'LIKE', '%' . $search . '%');
            });
        }
        $customers = $query->paginate(15);

        return view('customers.index', compact('customers'));
    }

    public function show(Customer $customer) : View
    {
        return view('customers.show', compact('customer'));
    }

    public function edit(Customer $customer): View
    {
        return view('customers.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer): RedirectResponse
    {
        $customer->update($request->all());
        return redirect()->route('customers.index')
            ->with('alert-msg', 'Cliente atualizado com sucesso!')
            ->with('alert-type', 'success');
    }

    public function destroy(Customer $customer): RedirectResponse
    {
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
    }
}
