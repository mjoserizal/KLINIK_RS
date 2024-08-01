<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Dokter;
use App\Models\User;
use App\Models\Poli;

class DokterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $polies = Poli::pluck('nama')->toArray(); // Fetch all poli names

        foreach (range(1, 3) as $index) {
            // Create a new User
            $user = User::factory()->create([
                'role' => 3, // Assuming 3 for dokter
            ]);

            // Create a new Dokter
            Dokter::create([
                'nip' => 'NIP' . $index,
                'nama' => 'Dokter ' . $index,
                'no_hp' => '08' . str_pad($index, 11, '0', STR_PAD_LEFT),
                'alamat' => 'Alamat Dokter ' . $index,
                'poli' => $polies[array_rand($polies)], // Randomly assign a poli
                'status' => 1,
                'user_id' => $user->id,
            ]);
        }
    }
}
