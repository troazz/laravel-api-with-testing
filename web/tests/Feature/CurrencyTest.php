<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use App\Models\Currency;

class CurrencyTest extends TestCase
{
    use WithoutMiddleware;
    use DatabaseTransactions;

    public function testListAll()
    {
        factory(Currency::class, 5)->create();

        $response = $this->withHeaders([
            'X-Header' => 'XMLHttpRequest',
        ])->json('GET', '/api/currencies');

        $response
            ->assertStatus(200)
            ->assertJson([
                'current_page' => 1,
                'data' => true
            ]);
    }

    public function testListAllWithParam()
    {
        factory(Currency::class, 5)->create();

        $response = $this->withHeaders([
            'X-Header' => 'XMLHttpRequest',
        ])->json('GET', '/api/currencies', ['page' => 2, 'per_page' => 2, 'q' => 'a', 'sort' => 'code-asc']);

        $response
            ->assertStatus(200)
            ->assertJson([
                'current_page' => 2,
                'data' => true
            ]);
    }

    public function testInvalidListAll()
    {
        factory(Currency::class, 5)->create();

        $response = $this->withHeaders([
            'X-Header' => 'XMLHttpRequest',
        ])->json('GET', '/api/currencies', ['page' => '2asa', 'per_page' => 'as', 'q' => 'a']);

        $response
            ->assertStatus(400)
            ->assertJson([
                'message' => 'Parameters validation error.',
                'errors' => true
            ]);
    }

    public function testDetail()
    {
        $currency = factory(Currency::class)->create();

        $response = $this->withHeaders([
            'X-Header' => 'XMLHttpRequest',
        ])->json('GET', '/api/currencies/'.$currency->id);

        $response
            ->assertStatus(200)
            ->assertJson($currency->toArray());
    }

    public function testCreate()
    {
        $data = ['name' => 'Indonesia Rupiah', 'symbol_location' => 'prefix', 'symbol' => 'Rp', 'code' => 'IDR'];

        $response = $this->withHeaders([
            'X-Header' => 'XMLHttpRequest',
        ])->json('POST', '/api/currencies', $data);

        $response
            ->assertStatus(201)
            ->assertJson($data);
    }

    public function testInvalidCreate()
    {
        $data = [];

        $response = $this->withHeaders([
            'X-Header' => 'XMLHttpRequest',
        ])->json('POST', '/api/currencies', $data);

        $response
            ->assertStatus(400)
            ->assertJson([
                'message' => 'Parameters validation error.',
                'errors' => true
            ]);
    }

    public function testUpdate()
    {
        $currency = factory(Currency::class)->create();

        $data = ['name' => 'Indonesia Rupiah', 'symbol_location' => 'prefix', 'symbol' => 'Rp', 'code' => 'IDR'];

        $response = $this->withHeaders([
            'X-Header' => 'XMLHttpRequest',
        ])->json('PUT', '/api/currencies/'.$currency->id, $data);

        $response
            ->assertStatus(200)
            ->assertJson(['id' => $currency->id] + $data);
    }

    public function testInvalidUpdate()
    {
        $currency = factory(Currency::class)->create();

        $data = ['name' => '', 'symbol_location' => '', 'symbol' => '', 'code' => ''];

        $response = $this->withHeaders([
            'X-Header' => 'XMLHttpRequest',
        ])->json('PUT', '/api/currencies/'.$currency->id, $data);

        $response
            ->assertStatus(400)
            ->assertJson([
                'message' => "Parameters validation error.",
                'errors' => true
            ]);
    }

    public function testDelete()
    {
        $currency = factory(Currency::class)->create();

        $response = $this->withHeaders([
            'X-Header' => 'XMLHttpRequest',
        ])->json('DELETE', '/api/currencies/'.$currency->id);

        $response
            ->assertStatus(200)
            ->assertJson(['message' => 'Delete success.']);

        $response = $this->withHeaders([
            'X-Header' => 'XMLHttpRequest',
        ])->json('GET', '/api/currencies/'.$currency->id);

        $response
            ->assertStatus(404)
            ->assertJson(['message' => 'Not Found.']);
    }
}
