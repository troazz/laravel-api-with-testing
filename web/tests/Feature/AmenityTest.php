<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use App\Models\Amenity;

class AmenityTest extends TestCase
{
    use WithoutMiddleware;
    use DatabaseTransactions;

    public function testListAll()
    {
        factory(Amenity::class, 5)->create();

        $count = Amenity::count();
        $response = $this->withHeaders([
            'X-Header' => 'XMLHttpRequest',
        ])->json('GET', '/api/amenities');

        $response
            ->assertStatus(200)
            ->assertJsonCount($count);
    }

    public function testListAllWithParam()
    {
        factory(Amenity::class, 5)->create();

        $response = $this->withHeaders([
            'X-Header' => 'XMLHttpRequest',
        ])->json('GET', '/api/amenities', ['page' => 2, 'per_page' => 2, 'q' => 'a']);

        $response
            ->assertStatus(200)
            ->assertJson([
                'current_page' => 2,
                'data' => true
            ]);
    }

    public function testInvalidListAll()
    {
        factory(Amenity::class, 5)->create();

        $response = $this->withHeaders([
            'X-Header' => 'XMLHttpRequest',
        ])->json('GET', '/api/amenities', ['page' => '2asa', 'per_page' => 'as', 'q' => 'a']);

        $response
            ->assertStatus(400)
            ->assertJson([
                'message' => 'Parameters validation error.',
                'errors' => true
            ]);
    }

    public function testDetail()
    {
        $amenity = factory(Amenity::class)->create();

        $response = $this->withHeaders([
            'X-Header' => 'XMLHttpRequest',
        ])->json('GET', '/api/amenities/'.$amenity->id);

        $response
            ->assertStatus(200)
            ->assertJson($amenity->toArray());
    }

    public function testCreate()
    {
        $data = ['name' => 'this is the nama', 'description' => 'this is description of the amenity'];

        $response = $this->withHeaders([
            'X-Header' => 'XMLHttpRequest',
        ])->json('POST', '/api/amenities', $data);

        $response
            ->assertStatus(201)
            ->assertJson([
                'name' => $data['name'],
                'description' => $data['description']
            ]);
    }

    public function testInvalidCreate()
    {
        $data = [];

        $response = $this->withHeaders([
            'X-Header' => 'XMLHttpRequest',
        ])->json('POST', '/api/amenities', $data);

        $response
            ->assertStatus(400)
            ->assertJson([
                'message' => 'Parameters validation error.',
                'errors' => true
            ]);
    }

    public function testUpdate()
    {
        $amenity = factory(Amenity::class)->create();

        $data = ['name' => 'Breakfast', 'description' => 'Only morning breakfast'];

        $response = $this->withHeaders([
            'X-Header' => 'XMLHttpRequest',
        ])->json('PUT', '/api/amenities/'.$amenity->id, $data);

        $response
            ->assertStatus(200)
            ->assertJson([
                'id' => $amenity->id,
                'name' => $data['name'],
                'description' => $data['description']
            ]);
    }

    public function testInvalidUpdate()
    {
        $amenity = factory(Amenity::class)->create();

        $data = ['name' => '', 'description' => 'this is description of the amenity'];

        $response = $this->withHeaders([
            'X-Header' => 'XMLHttpRequest',
        ])->json('PUT', '/api/amenities/'.$amenity->id, $data);

        $response
            ->assertStatus(400)
            ->assertJson([
                'message' => "Parameters validation error.",
                'errors' => true
            ]);
    }

    public function testDelete()
    {
        $amenity = factory(Amenity::class)->create();

        $response = $this->withHeaders([
            'X-Header' => 'XMLHttpRequest',
        ])->json('DELETE', '/api/amenities/'.$amenity->id);

        $response
            ->assertStatus(200)
            ->assertJson(['message' => 'Delete success.']);

        $response = $this->withHeaders([
            'X-Header' => 'XMLHttpRequest',
        ])->json('GET', '/api/amenities/'.$amenity->id);

        $response
            ->assertStatus(404)
            ->assertJson(['message' => 'Not Found.']);
    }
}
