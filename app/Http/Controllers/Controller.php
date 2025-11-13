<?php

namespace App\Http\Controllers;

abstract class Controller
{
    protected function responseSuccess($data = null, $status = 200) {
        return response()->json($data, $status);
    }

    protected function responseError($error = null) {
        return response()->json([
            'error' => $error->getMessage()
        ]);
    }
}
