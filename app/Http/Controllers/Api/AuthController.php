<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $validator = Validator::make(
            $data,
            [
                'email' => 'required',
                'password' => 'required',
            ]
        );

        try {
            if ($validator->fails()) {
                throw new \Exception("INVALID_PARAMETER");
            }
            $credentials = $request->only('email', 'password');
            if (Auth::attempt($credentials)) {
                // Authentication passed...
                $user = Auth::user();
                $user->api_token = \Illuminate\Support\Str::random(60);
                $user->token_expiration = new \DateTime('+30 minutes');
                $user->save();

                return response()->json(
                    [
                        "code" => "SUCCESS",
                        "data" => [
                            "email" => $user->email,
                            "api_token" => $user->api_token,
                            "token_expiration" => $user->token_expiration->format(\DateTime::ATOM)
                        ]
                    ]
                );
            } else {
                throw new \Exception("INVALID_CREDENTIAL");
            }
        } catch (\Exception $e) {
            return response()->json(
                [
                    "code" => $e->getMessage(),
                    "data" => [
                    ]
                ],
                422
            );
        }
    }
}
