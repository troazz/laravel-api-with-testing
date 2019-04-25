<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;

class APIController extends Controller
{
    protected $repo;
    protected $acceptedParams;

    public function index()
    {
        $params = request(['page', 'per_page', 'q', 'sort']);

        return $this->res($this->repo->get($params));
    }

    /**
     * create new amenity.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create()
    {
        $data = request($this->acceptedParams);

        return $this->res($this->repo->create($data));
    }

    /**
     * update existing amenity.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($id)
    {
        $data = request($this->acceptedParams);

        return $this->res($this->repo->update($id, $data));
    }

    /**
     * get existing amenity.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function find($id)
    {
        return $this->res($this->repo->find($id));
    }

    /**
     * delete existing amenity.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete($id)
    {
        return $this->res($this->repo->delete($id));
    }

    protected function res($response)
    {
        return response()->json($response['data'], $response['code']);
    }
}
