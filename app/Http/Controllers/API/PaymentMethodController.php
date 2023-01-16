<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Models\PaymentMethod;
use App\Services\ResponseApi;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;

class PaymentMethodController extends Controller
{
    protected $responseApi;

    public function __construct(ResponseApi $responseApi)
    {
        $this->responseApi = $responseApi;
    }

    public function index()
    {
        try {
            $data = PaymentMethod::get();
            if ($data->count() <= 0) {
                $data = [];
            }
            return $this->responseApi->response(true, Response::HTTP_OK, 'Success get all payment methods', $data);
        } catch (\Throwable $th) {
            return $this->responseApi->response(false, Response::HTTP_INTERNAL_SERVER_ERROR, $th->getMessage());
        }
    }
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
        ]);
        try {
            $payment = PaymentMethod::create($data);
            return $this->responseApi->response(true, Response::HTTP_CREATED, 'Success create new payment method', $payment);
        } catch (\Throwable $th) {
            return $this->responseApi->response(false, Response::HTTP_INTERNAL_SERVER_ERROR, $th->getMessage());
        }
    }
    public function update(Request $request, $id)
    {
        try {
            $payment = PaymentMethod::find($id);
            if ($payment == null) {
                return $this->responseApi->response(false, Response::HTTP_NOT_FOUND, 'Payment method not found!');
            }
            $payment->update($request->only('name', 'amount', 'price', 'unit_id'));
            return $this->responseApi->response(true, Response::HTTP_OK, 'Success update payment method', $payment->fresh());
        } catch (\Throwable $th) {
            return $this->responseApi->response(false, Response::HTTP_INTERNAL_SERVER_ERROR, $th->getMessage());
        }
    }
    public function destroy($id)
    {
        try {
            $payment = PaymentMethod::find($id);
            if ($payment == null) {
                return $this->responseApi->response(false, Response::HTTP_NOT_FOUND, 'Payment method not found!');
            }
            if ($payment->sale != null) {
                foreach ($payment->sale as $sale) {
                    foreach ($sale->sale_details as $detail) {
                        $detail->delete();
                    }
                    $sale->delete();
                }
            }
            $payment->delete();
            return $this->responseApi->response(true, Response::HTTP_OK, 'Success delete payment method');
        } catch (\Throwable $th) {
            return $this->responseApi->response(false, Response::HTTP_INTERNAL_SERVER_ERROR, $th->getMessage());
        }
    }
}
