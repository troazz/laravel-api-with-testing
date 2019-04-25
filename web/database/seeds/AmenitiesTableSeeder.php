<?php

use App\Models\Amenity;
use Illuminate\Database\Seeder;

class AmenitiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $amenities = collect(['Swimmming Pool', 'Breakfast', 'Gym', 'Sky Dinning', 'Spa', 'Chiffon Lounge', 'Cashmere Restaurant']);
        $amenities->each(function ($name) {
            $amenity = Amenity::where('name', $name)->first();
            if (! $amenity) {
                factory(Amenity::class)->create([
                    'name' => $name,
                ]);
            }
        });
    }
}
