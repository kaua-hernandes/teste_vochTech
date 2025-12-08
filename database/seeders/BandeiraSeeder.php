<?php

namespace Database\Seeders;

use App\Models\Bandeira;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BandeiraSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Bandeira::factory()->count(10)->create();
    }
}
