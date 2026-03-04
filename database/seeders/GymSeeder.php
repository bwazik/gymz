<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Gym;
use App\Traits\TruncatableTables;
use Illuminate\Database\Seeder;

class GymSeeder extends Seeder
{
    use TruncatableTables;

    public function run(): void
    {
        $this->truncateTables(['gyms']);

        $city = City::where('name', 'بنها')->first();

        if (!$city) {
            return;
        }

        $gymNames = ['بادي أرت', 'فيت اند ليفت', 'ايجو', 'اد فيت', 'هاني باور', 'جولدن'];

        foreach ($gymNames as $name) {
            Gym::firstOrCreate(
                ['name' => $name, 'city_id' => $city->id],
                ['is_active' => true]
            );
        }
    }
}
