<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Response;

class TokenController extends Controller
{
    /**
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function validateToken(Request $request)
    {
        $token = $request->header('Authorization');

        if (!$token) {
            return Response::json(['error' => 'Token not provided'], 401);
        }

        if (strpos($token, 'Bearer ') === 0) {
            $token = substr($token, 7);
        }

        $user = User::where('api_token', $token)->first();

        if ($user) {
            return Response::json(['valid' => true], 200);
        } else {
            return Response::json(['valid' => false], 401);
        }
    }
}
