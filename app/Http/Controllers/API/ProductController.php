<?php

namespace App\Http\Controllers\API;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Services\ResponseApi;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends Controller
{
    protected $responseApi;

    public function __construct(ResponseApi $responseApi)
    {
        $this->responseApi = $responseApi;
    }
    
    public function index()
    {
        try {
            $data = Product::with('product_variants')->get();
            if ($data->count() <= 0) {
                $data = [];
            }
            return $this->responseApi->response(true, Response::HTTP_OK, 'Success get all products', ProductResource::collection($data));
        } catch (\Throwable $th) {
            return $this->responseApi->response(false, Response::HTTP_INTERNAL_SERVER_ERROR, $th->getMessage());
        }
    }
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'description' => 'required',
            'price' => 'required|numeric'
        ]);
        try {
            $product = Product::create($data);
            return $this->responseApi->response(true, Response::HTTP_CREATED, 'Success create new product', new ProductResource($product));
        } catch (\Throwable $th) {
            return $this->responseApi->response(false, Response::HTTP_INTERNAL_SERVER_ERROR, $th->getMessage());
        }
    }
    public function show($id)
    {
        try {
            $product = Product::find($id);
            if ($product == null) {
                return $this->responseApi->response(false, Response::HTTP_NOT_FOUND, 'Product not found!');
            }
            return $this->responseApi->response(true, Response::HTTP_OK, 'Success get product by id', new ProductResource($product));
        } catch (\Throwable $th) {
            return $this->responseApi->response(false, Response::HTTP_INTERNAL_SERVER_ERROR, $th->getMessage());
        }
    }
    public function update(Request $request, $id)
    {
        try {
            $product = Product::find($id);
            if ($product == null) {
                return $this->responseApi->response(false, Response::HTTP_NOT_FOUND, 'Product not found!');
            }
            $product->update($request->only('name', 'amount', 'price', 'unit_id'));
            return $this->responseApi->response(true, Response::HTTP_OK, 'Success update product', new ProductResource($product->fresh()));
        } catch (\Throwable $th) {
            return $this->responseApi->response(false, Response::HTTP_INTERNAL_SERVER_ERROR, $th->getMessage());
        }
    }
    public function destroy($id)
    {
        try {
            $product = Product::find($id);
            if ($product == null) {
                return $this->responseApi->response(false, Response::HTTP_NOT_FOUND, 'Product not found!');
            }
            foreach ($product->product_variants as $variant) {
                $variant->delete();
            }
            $product->delete();
            return $this->responseApi->response(true, Response::HTTP_OK, 'Success delete product');
        } catch (\Throwable $th) {
            return $this->responseApi->response(false, Response::HTTP_INTERNAL_SERVER_ERROR, $th->getMessage());
        }
    }
}
