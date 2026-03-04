<?php

namespace Database\Seeders;

use App\Models\User;
use App\Traits\TruncatableTables;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    use TruncatableTables;

    public function run(): void
    {
        $this->truncateTables(['users']);

        User::create([
            'name' => 'عبدالله محمد',
            'email' => 'bwazik@outlook.com',
            'password' => Hash::make('bwazik@outlook.com'),
            'is_admin' => true,
        ]);

        User::factory(10)->create();
    }
}
