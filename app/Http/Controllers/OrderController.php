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
use App\Http\Requests\OrderRequest;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Order::class, 'order');
    }

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
        $this->authorize('minhasEncomendas', Order::class);

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
    }

    public function update(OrderRequest $request, Order $order): RedirectResponse
    {   
        $order->update($request->validated());

        if($request->status == 'closed'){
            $this->generateReceipt($order);
        }
        return redirect()->route('orders.index')
            ->with('alert-msg', "Encomenda #{$order->id} atualizada com sucesso!")
            ->with('alert-type', 'success');
    }

    public function store(OrderRequest $request): RedirectResponse
    {
        Order::create($request->validated());

        return redirect()->route('orders.index')
            ->with('alert-msg', 'Encomenda criada com sucesso!')
            ->with('alert-type', 'success');
    }

    public function destroy(Order $order): RedirectResponse
    {   
        $order->orderItems()->delete();

        $order->delete();

        return redirect()->route('orders.index')             
            ->with('alert-msg', "Order #{$order->id} apagada com sucesso!")
            ->with('alert-type', 'success');
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