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
                'nama' => 'John Doe',
                'tmp_lahir' => 'Jakarta',
                'tgl_lahir' => '1990-01-01',
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
            [
                'no_rm' => 'RM002',
                'nama' => 'Jane Doe',
                'tmp_lahir' => 'Bandung',
                'tgl_lahir' => '1985-02-02',
                'jk' => 'Perempuan',
                'alamat_lengkap' => 'Jl. Sejahtera No. 2',
                'kelurahan' => 'Kelurahan X',
                'kecamatan' => 'Kecamatan Y',
                'kabupaten' => 'Kabupaten Z',
                'kodepos' => '54321',
                'agama' => 'Kristen',
                'status_menikah' => 'Menikah',
                'pendidikan' => 'S2',
                'pekerjaan' => 'PNS',
                'kewarganegaraan' => 'WNI',
                'no_hp' => '089876543210',
            ],
        ];

        foreach ($data as $record) {
            Pasien::create($record);
        }
    }
}
