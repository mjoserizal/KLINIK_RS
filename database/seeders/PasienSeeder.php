<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pasien;

class PasienSeeder extends Seeder
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
                'no_rm' => 'RM001',
                'nama' => 'Mohammad Jose Rizal',
                'tmp_lahir' => 'Jakarta',
                'tgl_lahir' => '2002-09-04',
                'jk' => 'Laki-Laki',
                'alamat_lengkap' => 'Jl. Merdeka No. 1',
                'kelurahan' => 'Kelurahan A',
                'kecamatan' => 'Kecamatan B',
                'kabupaten' => 'Kabupaten C',
                'kodepos' => '12345',
                'agama' => 'Islam',
                'status_menikah' => 'Belum Menikah',
                'pendidikan' => 'S1',
                'pekerjaan' => 'Swasta',
                'kewarganegaraan' => 'WNI',
                'no_hp' => '081234567890',
            ],

        ];

        foreach ($data as $record) {
            Pasien::create($record);
        }
    }
}
