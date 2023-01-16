<?php

namespace App\Http\Controllers\API;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Services\ResponseApi;
use App\Models\ProductVariant;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Http\Resources\ProductVariantResource;
use App\Http\Resources\VariantResource;
use Symfony\Component\HttpFoundation\Response;

class ProductVariantController extends Controller
{
    protected $responseApi;

    public function __construct(ResponseApi $responseApi)
    {
        $this->responseApi = $responseApi;
    }
    
    public function index($id)
    {
        try {
            $product = Product::find($id);
            if ($product == null) {
                return $this->responseApi->response(false, Response::HTTP_NOT_FOUND, 'Product not found!');
            }
            return $this->responseApi->response(true, Response::HTTP_OK, 'Success get variants by product', new VariantResource($product));
        } catch (\Throwable $th) {
            return $this->responseApi->response(false, Response::HTTP_INTERNAL_SERVER_ERROR, $th->getMessage());
        }
    }
    public function store($id, Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'additional_price' => 'required|numeric'
        ]);
        try {
            $product = Product::find($id);
            if ($product == null) {
                return $this->responseApi->response(false, Response::HTTP_NOT_FOUND, 'Product not found!');
            }
            $product->product_variants()->create($data);
            return $this->responseApi->response(true, Response::HTTP_CREATED, "Success create new variant by product", new VariantResource($product));
        } catch (\Throwable $th) {
            return $this->responseApi->response(false, Response::HTTP_INTERNAL_SERVER_ERROR, $th->getMessage());
        }
    }
    public function update($idProduct, Request $request, $id)
    {
        try {
            $product = Product::find($idProduct);
            if ($product == null) {
                return $this->responseApi->response(false, Response::HTTP_NOT_FOUND, 'Product not found!');
            }
            $variant = $product->product_variants->where('id', $id)->first();
            if ($variant == null) {
                return $this->responseApi->response(false, Response::HTTP_NOT_FOUND, 'Variant not found!');
            }
            $variant->update($request->only('name', 'additional_price'));
            return $this->responseApi->response(true, Response::HTTP_OK, 'Success update variant', new VariantResource($product));
        } catch (\Throwable $th) {
            return $this->responseApi->response(false, Response::HTTP_INTERNAL_SERVER_ERROR, $th->getMessage());
        }
    }
    public function destroy($idProduct, $id)
    {
        try {
            $product = Product::find($idProduct);
            if ($product == null) {
                return $this->responseApi->response(false, Response::HTTP_NOT_FOUND, 'Product not found!');
            }
            $variant = $product->product_variants->where('id', $id)->first();
            if ($variant == null) {
                return $this->responseApi->response(false, Response::HTTP_NOT_FOUND, 'Variant not found!');
            }
            $variant->delete();
            return $this->responseApi->response(true, Response::HTTP_OK, 'Success delete variant');
        } catch (\Throwable $th) {
            return $this->responseApi->response(false, Response::HTTP_INTERNAL_SERVER_ERROR, $th->getMessage());
        }
    }
}
