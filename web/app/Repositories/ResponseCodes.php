<?php
namespace App\Repositories;

trait ResponseCodes {

    public function invalidated($errors)
    {
        $errors = collect($errors)->map(function ($error) {
            return $error[0];
        });

        return $this->bad([
            'message' => 'Parameters validation error.',
            'errors'  => $errors,
        ]);
    }

    function ok($data)
    {
        return [
            'code' => 200,
            'data' => $data,
        ];
    }

    function created($data)
    {
        return [
            'code' => 201,
            'data' => $data,
        ];
    }

    function bad($data)
    {
        return [
            'code' => 400,
            'data' => $data,
        ];
    }

    function ise($data = null)
    {
        if (empty($data)) {
            $data = [
                'message' => 'Internal Server Error.',
            ];
        }

        return [
            'code' => 500,
            'data' => $data,
        ];
    }

    function notFound($data = null)
    {
        if (empty($data)) {
            $data = [
                'message' => 'Not Found.',
            ];
        }

        return [
            'code' => 404,
            'data' => $data,
        ];
    }

    function unauthorized($data = null)
    {
        if (empty($data)) {
            $data = [
                'message' => 'Unauthenticated.',
            ];
        }

        return [
            'code' => 401,
            'data' => $data,
        ];
    }
}
