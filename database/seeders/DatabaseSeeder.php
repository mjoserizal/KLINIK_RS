<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            PoliSeeder::class,
            UserSeeder::class,
            DokterSeeder::class,
            PasienSeeder::class,
            RekamLukaBakarSeeder::class,
        ]);
    }
}
