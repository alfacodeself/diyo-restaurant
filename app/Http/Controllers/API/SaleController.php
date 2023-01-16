<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Services\ResponseApi;
use App\Http\Controllers\Controller;
use App\Http\Resources\CartResource;
use App\Models\Cart;
use App\Models\Sale;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\Response;

class SaleController extends Controller
{
    protected $responseApi;

    public function __construct(ResponseApi $responseApi)
    {
        $this->responseApi = $responseApi;
    }
    public function index()
    {
        try {
            $data = Sale::with('sale_details.cart.variant_product_carts', 'payment_method')->get()->map(function ($s) {
                $carts = [];
                foreach ($s->sale_details as $detail) {
                    array_push($carts, new CartResource($detail->cart));
                }
                return [
                    'id' => $s->id,
                    'cart' => $carts,
                    'total' => $s->total,
                    'created' => Carbon::parse($s->created_at)->format('d F Y H:i:s'),
                    'payment_method' => $s->payment_method->name
                ];
            });
            if ($data->count() <= 0) {
                $data = [];
            }
            return $this->responseApi->response(true, Response::HTTP_OK, 'Success get all sales', $data);
        } catch (\Throwable $th) {
            return $this->responseApi->response(false, Response::HTTP_INTERNAL_SERVER_ERROR, $th->getMessage());
        }
    }
    public function store(Request $request)
    {
        if (!$request->has('carts') || count($request->carts) <= 0) {
            return $this->responseApi->response(false, Response::HTTP_UNPROCESSABLE_ENTITY, 'Cart empty. Please add your cart');
        }
        // Validate data
        $request->validate([
            'payment_method_id' => 'required|numeric',
            'carts.*.cart_id' => 'required|numeric',
        ]);
        // Filter Cart ID
        $idCarts = [];
        foreach ($request->carts as $cart) {
            array_push($idCarts, $cart['cart_id']);
        }
        // Validate Cart
        $carts = Cart::whereIn('id', $idCarts)->get();
        if ($carts->count() != count($request->carts)) {
            return $this->responseApi->response(false, Response::HTTP_UNPROCESSABLE_ENTITY, 'Invalid Cart ID. Please check carefully');
        }
        foreach ($carts as $cart) {
            if ($cart->sale_details->count() > 0) {
                return $this->responseApi->response(false, Response::HTTP_UNPROCESSABLE_ENTITY, 'Cart with id ' . $cart->id . ' has been sale.');
            }
        }
        try {
            $sale = Sale::create([
                'id' => 'S-' . time() . '-' . Carbon::now()->format('dmy'),
                'payment_method_id' => $request->payment_method_id,
                'total' => $carts->sum('total'),
                'created_at' => Carbon::now()
            ]);
            foreach ($carts as $cart) {
                $sale->sale_details()->create(['cart_id' => $cart->id]);
            }
            return $this->responseApi->response(true, Response::HTTP_OK, 'Success create new sale');
        } catch (\Throwable $th) {
            return $this->responseApi->response(false, Response::HTTP_INTERNAL_SERVER_ERROR, $th->getMessage());
        }
    }
    public function show($id)
    { 
        try {
            $sale = Sale::find($id);
            if ($sale == null) {
                return $this->responseApi->response(false, Response::HTTP_NOT_FOUND, 'Sale not found!');
            }
            $carts = [];
            foreach ($sale->sale_details as $detail) {
                array_push($carts, new CartResource($detail->cart));
            }
            $data = [
                'id' => $sale->id,
                'cart' => $carts,
                'total' => $sale->total,
                'created' => Carbon::parse($sale->created_at)->format('d F Y H:i:s'),
                'payment_method' => $sale->payment_method->name
            ];
            return $this->responseApi->response(true, Response::HTTP_OK, 'Success get sale by id', $data);
        } catch (\Throwable $th) {
            return $this->responseApi->response(false, Response::HTTP_INTERNAL_SERVER_ERROR, $th->getMessage());
        }
    }
}
