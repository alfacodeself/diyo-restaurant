<?php

namespace App\Http\Controllers\API;

use App\Models\Unit;
use Illuminate\Http\Request;
use App\Services\ResponseApi;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;

class UnitController extends Controller
{
    protected $responseApi;

    public function __construct(ResponseApi $responseApi)
    {
        $this->responseApi = $responseApi;
    }

    public function index()
    {
        try {
            $data = Unit::all();
            if ($data->count() <= 0) {
                $data = [];
            }
            return $this->responseApi->response(true, Response::HTTP_OK, 'Success get all units', $data);
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
            $unit = Unit::create($data);
            return $this->responseApi->response(true, Response::HTTP_CREATED, 'Success create new unit', $unit);
        } catch (\Throwable $th) {
            return $this->responseApi->response(false, Response::HTTP_INTERNAL_SERVER_ERROR, $th->getMessage());
        }
    }
    public function update(Request $request, $id)
    {
        try {
            $unit = Unit::find($id);
            if ($unit == null) {
                return $this->responseApi->response(false, Response::HTTP_NOT_FOUND, 'Unit not found!');
            }
            $unit->update($request->only('name'));
            return $this->responseApi->response(true, Response::HTTP_OK, 'Success update unit', $unit->fresh());
        } catch (\Throwable $th) {
            return $this->responseApi->response(false, Response::HTTP_INTERNAL_SERVER_ERROR, $th->getMessage());
        }
    }
    public function destroy($id)
    {
        try {
            $unit = Unit::find($id);
            if ($unit == null) {
                return $this->responseApi->response(false, Response::HTTP_NOT_FOUND, 'Unit not found!');
            }
            $unit->delete();
            return $this->responseApi->response(true, Response::HTTP_OK, 'Success delete unit');
        } catch (\Throwable $th) {
            return $this->responseApi->response(false, Response::HTTP_INTERNAL_SERVER_ERROR, $th->getMessage());
        }
    }
}
