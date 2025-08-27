<?php

namespace App\Traits;

trait ApiResponse{
    protected function successful($message, $data, $status = 200) {
        return response()->json([
            'message' => $message,
            'data' => $data,
            'status' => $status
        ], $status);

    }

    protected function ok($message, $data = null) {
        return $this->successful($message, $data, 200);
    }
}