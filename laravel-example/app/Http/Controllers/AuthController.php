<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    use ApiResponse;

    function login(Request $request) {
        return $this->ok("Hello camper!", ['example' => 'example data']);
    }
}