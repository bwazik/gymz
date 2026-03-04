<?php

namespace Database\Seeders;

use App\Models\City;
use App\Traits\TruncatableTables;
use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{
    use TruncatableTables;

    public function run(): void
    {
        $this->truncateTables(['cities']);

        City::firstOrCreate(
            ['name' => 'بنها'],
            ['is_active' => true]
        );
    }
}
