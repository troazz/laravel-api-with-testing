<?php

use App\Models\Amenity;
use App\Models\Hotel;
use App\Models\Room;
use Illuminate\Database\Seeder;

class HotelsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
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
    }
}
