<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RekamLukaBakar;

class RekamLukaBakarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'no_rekam' => 'RBK001',
                'tgl_rekam' => '2024-07-28',
                'pasien_id' => 1,
                'berat_badan' => 70,
                'persen_luka_bakar' => 30,
                'status' => 1,
            ],
            [
                'no_rekam' => 'RBK002',
                'tgl_rekam' => '2024-07-29',
                'pasien_id' => 2,
                'berat_badan' => 65,
                'persen_luka_bakar' => 40,
                'status' => 1,
            ],
        ];

        foreach ($data as $record) {
            $record['cairan'] = 4 * $record['berat_badan'] * $record['persen_luka_bakar'];
            RekamLukaBakar::create($record);
        }
    }
}
