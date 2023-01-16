<?php

namespace App\Services;


class ResponseApi
{
    public function response($success = true, $statusCode, $message, $data = null)
    {
        $res = [
            'success' => $success,
            'status_code' => $statusCode,
            'message' => $message
        ];
        if ($data != null) $res['data'] = $data;
        return response()->json($res, $statusCode);
    }
}
