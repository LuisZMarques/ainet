<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class OrderController extends Controller
{
    public function index(Request $request) : View
    {
        if (Auth::user()->isAdmin() || Auth::user()->isEmployee()) {
        
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

        } else {
            return view('home')->with('alert-msg', 'Não tem permissões para ver encomendas!')->with('alert-type', 'danger');
        }
    }
    

    public function create(): View
    {  
        if(Auth::user()->isCustomer())
            return view('orders.create');
        else{
            return view('home')->with('alert-msg', 'Não tem permissões para criar encomendas!')->with('alert-type', 'danger');
        }
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
            $query = Order::query()->where('customer_id', $request->user()->customer->id);

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
            
            return view('orders.minhas', compact('orders'));
        }else{
            return view('home')->with('alert-msg', 'Como não é cliente não tem acesso a encomendas próprias.')->with('alert-type', 'danger');
        }
    }

    public function update(Request $request, Order $order): RedirectResponse
    {   
        if(Auth::user()->isAdmin() || Auth::user()->isEmployee()){
            $order->update($request->all());

            if($request->status == 'closed'){
                $this->generateReceipt($order);
            }
            return redirect()->route('orders.index')
                ->with('alert-msg', "Encomenda #{$order->id} atualizada com sucesso!")
                ->with('alert-type', 'success');
        }else{
            return redirect()->route('home')->with('alert-msg', 'Não tem permissões para atualizar encomendas!')->with('alert-type', 'danger');
        }
    }

    public function store(Request $request): RedirectResponse
    {
        if(Auth::user()->isCustomer()){
            Order::create($request->all());

            return redirect()->route('orders.index')
                ->with('alert-msg', 'Encomenda criada com sucesso!')
                ->with('alert-type', 'success');
        }else{
            return redirect()->route('home')->with('alert-msg', 'Não tem permissões para criar encomendas!')->with('alert-type', 'danger');
        }
    }

    public function destroy(Order $order): RedirectResponse
    {   
        if(Auth::user()->isAdmin()){
            $order->orderItems()->delete();

            $order->delete();

            return redirect()->route('orders.index')             
                ->with('alert-msg', "Order #{$order->id} apagada com sucesso!")
                ->with('alert-type', 'success');
        }else{
            return redirect()->route('home')->with('alert-msg', 'Não tem permissões para apagar encomendas!')->with('alert-type', 'danger');
        }
    }

    private function generateReceipt(Order $order): void
    {
        $pdf = new Dompdf();
        $html = view('receipt', compact('order'))->render();

        $pdf->loadHtml($html);
        $pdf->render();

        $filename = 'receipt_' . $order->id . '.pdf';

        $storageDirectory =  'pdf_receipts/';

        if (!file_exists($storageDirectory)) {
            mkdir($storageDirectory, 0777, true);
        }

        Storage::put($storageDirectory . '/' . $filename, $pdf->output());

        $order->receipt_url = $filename;
        $order->save();
    }

}