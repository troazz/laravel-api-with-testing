<?php

namespace App\Repositories;

use Validator;

class AuthRepository extends Repository
{
    public function login($credentials) {
        $validator = Validator::make($credentials, [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->invalidated($validator->errors());
        }

        if (! $token = auth('api')->attempt($credentials)) {
            return $this->unauthorized(['message' => 'Email or password doesn\'t match.']);
        }

        return $this->respondWithToken($token);
    }

    public function refresh()
    {
        return $this->respondWithToken(auth('api')->refresh());
    }

    public function user()
    {
        return $this->ok(auth('api')->user());
    }

    public function logout()
    {
        auth('api')->logout();

        return $this->ok(['message' => 'Successfully logged out.']);
    }

    protected function respondWithToken($token)
    {
        return $this->ok([
            'access_token' => $token,
            'token_type'   => 'bearer',
            'expires_in'   => auth('api')->factory()->getTTL() * 60,
            'user'         => auth('api')->user(),
        ]);
    }
}
