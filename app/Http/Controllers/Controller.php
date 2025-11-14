<?php

namespace App\Http\Controllers;

abstract class Controller
{
    protected function responseSuccess($data = null, $status = 200) {
        return response()->json($data, $status);
    }

    protected function responseError($error = null, $status = 400) {
        return response()->json([
            'message' => $error->getMessage()
        ], $status);
    }
}
