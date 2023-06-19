<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use function imagecreatefromjpeg;
use function imagecreatefrompng;
use function imagesx;
use function imagesy;
use function imagecopyresampled;
use function imagejpeg;
use function imagepng;
use function imagedestroy;
use function imagecolorallocatealpha;
use function imagefill;
use function imagecolortransparent;


class CartController extends Controller
{
    public function __construct()
    {
        // Apply the middlware 'can:access-cart' to all methods of the controlelr
        $this->middleware('can:access-cart')->except('addToCart');
    }
 
    public function show(): View
    {
        $cart = session('cart', []);
        $customer = Auth::user()->customer;
        return view('cart.show', compact('cart', 'customer'));
    }

    public function addToCart(Request $request): RedirectResponse
    {
        try {
            if(Auth::guest()){
                return redirect()->route('login')->with('alert-msg', 'Para adicionar produtos ao carrinho tem de estar autenticado!')->with('alert-type', 'danger');
            }
            if(!Auth::user()->isCustomer()){
                return redirect()->route('home')->with('alert-msg', 'Não tem permissões para adicionar produtos ao carrinho!')->with('alert-type', 'danger');
            }

            $cart = session('cart', []);
            $tshirtImageId = $request->input('idImage');
            $tshirtImageName = $request->input('nameImage');
            $tshirtImageUrl = $request->input('imageUrl');
            $imageFullUrl = 'storage/tshirt_images/' . $tshirtImageUrl;
            $tshirtSize = $request->input('size');
            $tshirtColor = $request->input('color');
            $tshirtQuantity = $request->input('quantity');
            $tshirtUnitPrice = $request->input('unitPrice');
            $tshirtSubTotal = $tshirtQuantity * $tshirtUnitPrice;
            $tshirtUniqueId = $tshirtImageId . $tshirtSize . $tshirtColor;
            
            if (array_key_exists($tshirtUniqueId, $cart)) {
                $url = route('cart.show');
                $alertType = 'warning';
                $htmlMessage = "Este pedido já tem tshirts com a imagem ' <strong>\"{$tshirtImageName}\"</strong> ', cor ' <strong>\"{$tshirtColor}\"</strong> ' e tamanho ' <strong>\"{$tshirtSize}\"</strong> ' adicionada ao carrinho!";
            } else {
                                
                if (empty($cart[0])) {
                    $cart[0] = [];
                }
                
                array_push($cart[0], $tshirtUniqueId);

                $tshirtPreviewImage = $this->generateTshirtPreviewImage($tshirtColor, $imageFullUrl, $tshirtUniqueId);

                $cart[$tshirtUniqueId] = [
                    'imageId' => $tshirtImageId,
                    'imageName' => $tshirtImageName,
                    'tshirtPreviewImage' => $tshirtPreviewImage,
                    'size' => $tshirtSize,
                    'color' => $tshirtColor,
                    'quantity' => $tshirtQuantity,
                    'unitPrice' => $tshirtUnitPrice,
                    'subTotal' => $tshirtSubTotal,
                ];
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

    public function removeFromCart(Request $request, $tshirtUniqueId): RedirectResponse
    {
        $cart = session('cart', []);
        if (array_key_exists($tshirtUniqueId, $cart)) {
            unset($cart[$tshirtUniqueId]);
    
            $key = array_search($tshirtUniqueId, $cart[0]);
            if ($key !== false) {
                unset($cart[0][$key]);
                $cart[0] = array_values($cart[0]);
            }
        }
        $request->session()->put('cart', $cart);
        $url = route('cart.show');
        $htmlMessage = "tshirt  <strong>\"{$tshirtUniqueId}\"</strong> foi removida do carrinho!";
        return back()
            ->with('alert-msg', $htmlMessage)
            ->with('alert-type', 'success');
        
    }
    public function store(Request $request): RedirectResponse
    {
        try {
            $cart = session('cart', []);
            $total = count($cart[0]);
            if ($total < 1) {
                $alertType = 'warning';
                $htmlMessage = "Não é possível confirmar a encomenda, porque o carrinho está vazio!";
            } else {
                $customer = Auth::user()->customer;
                DB::connection()->enableQueryLog();
                #Encomenda
                $order = new Order();
                $order->status = 'pending';
                $order->customer_id = $customer->id;
                $order->date = now();
                $order->total_price = (float) $request->input('totalPrice');
                $order->notes = $request->input('notes');
                $order->nif = $request->input('nif');
                $order->address = $request->input('address');
                $order->payment_type = $request->input('payment_type');
                $order->payment_ref = $request->input('payment_ref');
                $order->receipt_url = null;
                $order->save();

                DB::transaction(function () use ($cart, $order) {
                    foreach ($cart[0] as $tshirtUniqueId) {
                        $item = $cart[$tshirtUniqueId];
                
                        $orderItem = new OrderItem();
                        $orderItem->order_id = $order->id; 
                        $orderItem->tshirt_image_id = $item['imageId'];
                        $orderItem->size = $item['size'];
                        $orderItem->color_code = $item['color'];
                        $orderItem->qty = $item['quantity'];
                        $orderItem->unit_price = $item['unitPrice'];
                        $orderItem->sub_total = $item['subTotal'];
                        $orderItem->save();
                    }
                });

                $htmlMessage = "Foi confirmada a encomenda do customer #{$customer->id} <strong>\"{$customer->user->name}\"</strong>" ;
                $queryLog = DB::getQueryLog();
                foreach ($queryLog as $query) {
                    $htmlMessage .= "Query: " . $query['query'] . "\n";
                    $htmlMessage .= "Bindings: " . json_encode($query['bindings']) . "\n";
                }

                $request->session()->forget('cart');
                return redirect()->route('orders.minhas')
                    ->with('alert-msg', $htmlMessage)
                    ->with('alert-type', 'success');
            }
            
        } catch (\Exception $error) {
            $htmlMessage = "Não foi possível inserir as tshirts no carrinho, porque ocorreu um erro!" . $error;
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

    private function generateTshirtPreviewImage($tshirtColor, $imageFullUrl, $tshirtUniqueId)
    {
        $colorImage = imagecreatefromjpeg('storage/tshirt_base/' . $tshirtColor . '.jpg');

        $imageOnTshirt = imagecreatefrompng($imageFullUrl);

        $backgroundWidth = imagesx($colorImage);
        $backgroundHeight = imagesy($colorImage);
        $overlayWidth = imagesx($imageOnTshirt);
        $overlayHeight = imagesy($imageOnTshirt);
        
        $newOverlayWidth = 175;
        $newOverlayHeight = 200;

        $resizedImageOnTshirt = imagescale($imageOnTshirt, $newOverlayWidth, $newOverlayHeight);

        
        $x = ($backgroundWidth - $newOverlayWidth) / 2;
        $y = ($backgroundHeight - $newOverlayHeight) / 2;

        $combinedImage = imagecreatetruecolor($backgroundWidth, $backgroundHeight);
        $transparentColor = imagecolorallocatealpha($combinedImage, 0, 0, 0, 127);
        imagefill($combinedImage, 0, 0, $transparentColor);
        imagecolortransparent($combinedImage, $transparentColor);
        
        imagecopy($combinedImage, $colorImage, 0, 0, 0, 0, $backgroundWidth, $backgroundHeight);

        imagecopy($combinedImage, $resizedImageOnTshirt, $x, $y, 0, 0, $newOverlayWidth, $newOverlayHeight);

        $previewFolderPath = 'storage/preview/';
        if (!file_exists($previewFolderPath)) {
            mkdir($previewFolderPath, 0777, true);
        }

        $previewImagePath = 'storage/preview/'. $tshirtUniqueId . '.png';
        imagepng($combinedImage, $previewImagePath);

        return $previewImagePath;
    }


}
