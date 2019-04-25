<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use App\Models\Hotel;
use App\Models\Room;
use App\Models\Amenity;
use App\Models\Currency;

class HotelTest extends TestCase
{
    use WithoutMiddleware;
    use DatabaseTransactions;

    public function testListAll()
    {
        factory(Hotel::class, 5)->create();

        $response = $this->withHeaders([
            'X-Header' => 'XMLHttpRequest',
        ])->json('GET', '/api/hotels');

        $response
            ->assertStatus(200)
            ->assertJson([
                'current_page' => 1,
                'data' => true
            ]);
    }

    public function testListAllWithParam()
    {
        factory(Hotel::class, 5)
            ->create()
            ->each(function ($hotel) {
                foreach (range(1, rand(1, 5)) as $i) {
                    $room = factory(Room::class)
                        ->create(['hotel_id' => $hotel->id]);
                    $amenities = Amenity::take(rand(1, 10))
                        ->get()
                        ->pluck('id');
                    $room->amenities()->attach($amenities);
                    $hotel->rooms()->save($room);
                }
            });

        $response = $this->withHeaders([
            'X-Header' => 'XMLHttpRequest',
        ])->json('GET', '/api/hotels', ['page' => 2, 'per_page' => 2, 'q' => 'a']);

        $response
            ->assertStatus(200)
            ->assertJson([
                'current_page' => 2,
                'data' => true
            ]);
    }

    public function testInvalidListAll()
    {
        factory(Hotel::class, 5)
            ->create()
            ->each(function ($hotel) {
                foreach (range(1, rand(1, 5)) as $i) {
                    $room = factory(Room::class)
                        ->create(['hotel_id' => $hotel->id]);
                    $amenities = Amenity::inRandomOrder()
                        ->take(rand(1, 10))
                        ->get()
                        ->pluck('id');
                    $room->amenities()->attach($amenities);
                    $hotel->rooms()->save($room);
                }
            });

        $response = $this->withHeaders([
            'X-Header' => 'XMLHttpRequest',
        ])->json('GET', '/api/hotels', ['page' => '2asa', 'per_page' => 'as', 'q' => 'a']);

        $response
            ->assertStatus(400)
            ->assertJson([
                'message' => 'Parameters validation error.',
                'errors' => true
            ]);
    }

    public function testDetail()
    {
        $hotel = factory(Hotel::class)->create();

        $response = $this->withHeaders([
            'X-Header' => 'XMLHttpRequest',
        ])->json('GET', '/api/hotels/'.$hotel->id);

        $response
            ->assertStatus(200)
            ->assertJson($hotel->toArray());
    }

    public function testCreate()
    {
        $data = [
            'name' => 'name',
            'description' => 'hotel',
            'address' => 'address',
            'longitude' => '-60.6921280',
            'latitude' => '7.0796290',
            'commission_type' => 'percentage',
            'commission_amount' => '10',
            'rooms' => [
                [
                    'name' => 'room 1',
                    'description' => 'room 1 desc',
                    'access_code' => '1234567',
                    'max_occupancy' => 1,
                    'net_rate' => 100000,
                    'currency_id' => Currency::inRandomOrder()->first()->id,
                    'amenities_id' => Amenity::inRandomOrder()
                        ->take(rand(1, 10))
                        ->get()
                        ->pluck('id')
                ],
                [
                    'name' => 'room 2',
                    'description' => 'room 2 desc',
                    'access_code' => '2309402',
                    'max_occupancy' => 2,
                    'net_rate' => 120000,
                    'currency_id' => Currency::inRandomOrder()->first()->id
                ]
            ]
        ];

        $response = $this->withHeaders([
            'X-Header' => 'XMLHttpRequest',
        ])->json('POST', '/api/hotels', $data);

        $response
            ->assertStatus(201)
            ->assertJson([
                'name' => $data['name'],
                'description' => $data['description'],
                'rooms' => true
            ]);
    }

    public function testInvalidCreate()
    {
        $data = [];

        $response = $this->withHeaders([
            'X-Header' => 'XMLHttpRequest',
        ])->json('POST', '/api/hotels', $data);

        $response
            ->assertStatus(400)
            ->assertJson([
                'message' => 'Parameters validation error.',
                'errors' => true
            ]);
    }

    public function testUpdate()
    {
        $hotel = factory(Hotel::class)
            ->create();

        foreach (range(1, rand(1, 5)) as $i) {
            $room = factory(Room::class)
                ->create(['hotel_id' => $hotel->id]);
            $amenities = Amenity::inRandomOrder()
                ->take(rand(1, 10))
                ->get()
                ->pluck('id');
            $room->amenities()->attach($amenities);
            $hotel->rooms()->save($room);
        }

        $data = [
            'name' => 'name',
            'description' => 'hotel',
            'address' => 'address',
            'longitude' => '-60.6921280',
            'latitude' => '7.0796290',
            'commission_type' => 'percentage',
            'commission_amount' => '10',
            'rooms' => [
                [
                    'id' => $hotel->rooms->first()->id,
                    'name' => 'room 1',
                    'description' => 'room 1 desc',
                    'access_code' => '1234567',
                    'max_occupancy' => 1,
                    'net_rate' => 100000,
                    'currency_id' => Currency::inRandomOrder()->first()->id,
                    'amenities' => Amenity::inRandomOrder()
                        ->take(rand(1, 10))
                        ->get()
                        ->pluck('id')
                ],
                [
                    'name' => 'room 2',
                    'description' => 'room 2 desc',
                    'access_code' => '2309402',
                    'max_occupancy' => 2,
                    'net_rate' => 120000,
                    'currency_id' => Currency::inRandomOrder()->first()->id,
                    'amenities' => Amenity::inRandomOrder()
                        ->take(rand(1, 10))
                        ->get()
                        ->pluck('id')
                ]
            ]
        ];

        $response = $this->withHeaders([
            'X-Header' => 'XMLHttpRequest',
        ])->json('PUT', '/api/hotels/'.$hotel->id, $data);

        $response
            ->assertStatus(200)
            ->assertJson([
                'id' => $hotel->id,
                'name' => $data['name'],
                'description' => $data['description']
            ]);
    }

    public function testInvalidUpdate()
    {
        $hotel = factory(Hotel::class)
            ->create();

        foreach (range(1, rand(1, 5)) as $i) {
            $room = factory(Room::class)
                ->create(['hotel_id' => $hotel->id]);
            $amenities = Amenity::inRandomOrder()
                ->take(rand(1, 10))
                ->get()
                ->pluck('id');
            $room->amenities()->attach($amenities);
            $hotel->rooms()->save($room);
        }

        $data = ['name' => '', 'description' => 'this is description of the hotel'];

        $response = $this->withHeaders([
            'X-Header' => 'XMLHttpRequest',
        ])->json('PUT', '/api/hotels/'.$hotel->id, $data);

        $response
            ->assertStatus(400)
            ->assertJson([
                'message' => "Parameters validation error.",
                'errors' => true
            ]);
    }

    public function testDelete()
    {
        $hotel = factory(Hotel::class)
            ->create();

        foreach (range(1, rand(1, 5)) as $i) {
            $room = factory(Room::class)
                ->create(['hotel_id' => $hotel->id]);
            $amenities = Amenity::inRandomOrder()
                ->take(rand(1, 10))
                ->get()
                ->pluck('id');
            $room->amenities()->attach($amenities);
            $hotel->rooms()->save($room);
        }

        $response = $this->withHeaders([
            'X-Header' => 'XMLHttpRequest',
        ])->json('DELETE', '/api/hotels/'.$hotel->id);

        $response
            ->assertStatus(200)
            ->assertJson(['message' => 'Delete success.']);

        $response = $this->withHeaders([
            'X-Header' => 'XMLHttpRequest',
        ])->json('GET', '/api/hotels/'.$hotel->id);

        $response
            ->assertStatus(404)
            ->assertJson(['message' => 'Not Found.']);
    }
}
