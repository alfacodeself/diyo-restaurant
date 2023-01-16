<?php

namespace App\Http\Controllers\API;

use App\Models\Cart;
use App\Services\ResponseApi;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariant;
use Symfony\Component\HttpFoundation\Response;

class CartController extends Controller
{
    protected $responseApi;

    public function __construct(ResponseApi $responseApi)
    {
        $this->responseApi = $responseApi;
    }
    public function getAllCarts()
    {
        try {
            $data = Cart::with('variant_product_carts')->get();
            if ($data->count() <= 0) {
                $data = [];
            }
            return $this->responseApi->response(true, Response::HTTP_OK, 'Success get all carts', $data);
        } catch (\Throwable $th) {
            return $this->responseApi->response(false, Response::HTTP_INTERNAL_SERVER_ERROR, $th->getMessage());
        }
    }
    public function addProductToCart($idProduct)
    {
        try {
            // Choose the product
            $product = Product::find($idProduct);
            // If product not found
            if ($product == null) {
                return $this->responseApi->response(false, Response::HTTP_NOT_FOUND, 'Product not found!');
            }
            // Add product to cart
            $cart = $product->carts()->create([
                'name' => $product->name,
                'price' => $product->price,
                'total' => $product->price,
            ]);
            return $this->responseApi->response(true, Response::HTTP_OK, 'Success create new cart', $cart);
        } catch (\Throwable $th) {
            return $this->responseApi->response(false, Response::HTTP_INTERNAL_SERVER_ERROR, $th->getMessage());
        }
    }
    public function addVariantsToProductCart($idCart, $idVariant)
    {
        try {
            // Select customers cart
            $cart = Cart::find($idCart);
            // If cart not found
            if ($cart == null) {
                return $this->responseApi->response(false, Response::HTTP_NOT_FOUND, 'Cart not found!');
            }
            // Customer select the variant of product
            $variant = ProductVariant::find($idVariant);
            // If variant not found
            if ($variant == null) {
                return $this->responseApi->response(false, Response::HTTP_NOT_FOUND, "Product's variant not found!");
            }
            // If variant is not product's selected from cart
            if ($variant->product_id !== $cart->product_id) {
                return $this->responseApi->response(false, Response::HTTP_UNPROCESSABLE_ENTITY, "Product's variant is illegal!");
            }
            // Create variant's product cart
            $variantProduct = $cart->variant_product_carts()->create([
                'product_variant_id' => $variant->id,
                'name' => $variant->name,
                'price' => $variant->additional_price,
            ]);
            // Update total of cart
            $cart->update(['total' => $cart->total + $variantProduct->price]);
            return $this->responseApi->response(true, Response::HTTP_OK, 'Success create new cart', $cart->fresh());
        } catch (\Throwable $th) {
            return $this->responseApi->response(false, Response::HTTP_INTERNAL_SERVER_ERROR, $th->getMessage());
        }
    }
    public function deleteVariantInProductCart($idCart, $idVariant)
    {
        try {
             // Select customers cart
            $cart = Cart::find($idCart);
             // If cart not found
            if ($cart == null) {
                return $this->responseApi->response(false, Response::HTTP_NOT_FOUND, 'Cart not found!');
            }
            // Find cart product's variant that customers want to delete
            $cartVariant = $cart->variant_product_carts()->where('product_variant_id', $idVariant)->first();
            // if cart product's variant not found
            if ($cartVariant == null) {
                return $this->responseApi->response(false, Response::HTTP_NOT_FOUND, "Cart product's variant not found!");
            }
            // Update total of cart
            $cart->update(['total' => $cart->total - $cartVariant->price]);
            // delete cart product's variant not found
            $cartVariant->delete();
            return $this->responseApi->response(true, Response::HTTP_OK, "Success delete product's variant from cart", $cart->fresh());
        } catch (\Throwable $th) {
            return $this->responseApi->response(false, Response::HTTP_INTERNAL_SERVER_ERROR, $th->getMessage());
        }
    }
    public function deleteCart($idCart)
    {
        try {
            // Select customers cart
           $cart = Cart::find($idCart);
            // If cart not found
           if ($cart == null) {
               return $this->responseApi->response(false, Response::HTTP_NOT_FOUND, 'Cart not found!');
           }
            //Delete the variant
           foreach ($cart->variant_product_carts as $variant) {
                $variant->delete();
           }
           // delete cart product's variant not found
           $cart->delete();
           return $this->responseApi->response(true, Response::HTTP_OK, "Success delete cart");
       } catch (\Throwable $th) {
           return $this->responseApi->response(false, Response::HTTP_INTERNAL_SERVER_ERROR, $th->getMessage());
       }
    }
}
