<?php

namespace App\Http\Controllers\API;

use App\Repositories\AuthRepository;

class AuthController extends APIController
{
    public $authRepository;

    /**
     * Create a new AuthController instance.
     */
    public function __construct()
    {
        $this->authRepository = new AuthRepository();
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        $credentials = request(['email', 'password']);

        return $this->res($this->authRepository->login($credentials));
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return $this->res($this->authRepository->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        return $this->res($this->authRepository->logout());
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->res($this->authRepository->refresh());
    }
}
