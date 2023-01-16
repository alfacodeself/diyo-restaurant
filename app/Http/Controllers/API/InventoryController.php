<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\InventoryResource;
use App\Models\Inventory;
use App\Services\ResponseApi;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class InventoryController extends Controller
{
    protected $responseApi;

    public function __construct(ResponseApi $responseApi)
    {
        $this->responseApi = $responseApi;
    }
    
    public function index()
    {
        try {
            $data = Inventory::with('unit')->get();
            if ($data->count() <= 0) {
                $data = [];
            }
            return $this->responseApi->response(true, Response::HTTP_OK, 'Success get all inventories', InventoryResource::collection($data));
        } catch (\Throwable $th) {
            return $this->responseApi->response(false, Response::HTTP_INTERNAL_SERVER_ERROR, $th->getMessage());
        }
    }
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'amount' => 'required|numeric',
            'price' => 'required|numeric',
            'unit_id' => 'required'
        ]);
        try {
            $inventory = Inventory::create($data);
            return $this->responseApi->response(true, Response::HTTP_CREATED, 'Success create new inventory', new InventoryResource($inventory));
        } catch (\Throwable $th) {
            return $this->responseApi->response(false, Response::HTTP_INTERNAL_SERVER_ERROR, $th->getMessage());
        }
    }
    public function show($id)
    {
        try {
            $inventory = Inventory::find($id);
            if ($inventory == null) {
                return $this->responseApi->response(false, Response::HTTP_NOT_FOUND, 'Inventory not found!');
            }
            return $this->responseApi->response(true, Response::HTTP_OK, 'Success get inventory by id', new InventoryResource($inventory));
        } catch (\Throwable $th) {
            return $this->responseApi->response(false, Response::HTTP_INTERNAL_SERVER_ERROR, $th->getMessage());
        }
    }
    public function update(Request $request, $id)
    {
        try {
            $inventory = Inventory::find($id);
            if ($inventory == null) {
                return $this->responseApi->response(false, Response::HTTP_NOT_FOUND, 'Inventory not found!');
            }
            $inventory->update($request->only('name', 'amount', 'price', 'unit_id'));
            return $this->responseApi->response(true, Response::HTTP_OK, 'Success update inventory', new InventoryResource($inventory->fresh()));
        } catch (\Throwable $th) {
            return $this->responseApi->response(false, Response::HTTP_INTERNAL_SERVER_ERROR, $th->getMessage());
        }
    }
    public function destroy($id)
    {
        try {
            $inventory = Inventory::find($id);
            if ($inventory == null) {
                return $this->responseApi->response(false, Response::HTTP_NOT_FOUND, 'Inventory not found!');
            }
            $inventory->delete();
            return $this->responseApi->response(true, Response::HTTP_OK, 'Success delete inventory');
        } catch (\Throwable $th) {
            return $this->responseApi->response(false, Response::HTTP_INTERNAL_SERVER_ERROR, $th->getMessage());
        }
    }
}
