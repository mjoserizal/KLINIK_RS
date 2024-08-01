<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Poli;

class PoliSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Replace with your poli names
        $poliNames = [
            'Pediatri',
            'Kardiologi',
            'Neurologi',
            'Obstetri',
            'Oftalmologi',
        ];

        foreach ($poliNames as $name) {
            Poli::create(['nama' => $name]);
        }
    }
}
