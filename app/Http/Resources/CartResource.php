<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $data['product_id'] = $this->product_id;
        $data['price'] = $this->price;
        if ($this->variant_product_carts->count() > 0) {
            $data['variants'] = CartVariantResource::collection($this->variant_product_carts);
        }
        return $data;
    }
}
