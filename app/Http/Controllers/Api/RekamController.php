<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\Pasien;
use App\Models\RekamLukaBakar;

class RekamController extends Controller
{
    public function store(Request $request, $no_rm)
    {
        // Validasi data input
        $validatedData = $request->validate([
            'tgl_rekam' => 'required|date',
            'berat_badan' => 'required|numeric',
            'persen_luka_bakar' => 'required|numeric',
        ]);

        // Mencari pasien berdasarkan no_rm dari parameter URL
        $pasien = Pasien::where('no_rm', $no_rm)->first();

        if (!$pasien) {
            return response()->json(['message' => 'Pasien tidak ditemukan'], 404);
        }

        // Generate no_rekam otomatis
        $latestRekam = RekamLukaBakar::orderBy('created_at', 'desc')->first();
        $latestNoRekam = $latestRekam ? $latestRekam->no_rekam : 'RK00';
        $newNoRekam = 'RK' . str_pad((int)substr($latestNoRekam, 2) + 1, 3, '0', STR_PAD_LEFT);

        // Hitung cairan berdasarkan rumus
        $beratBadan = $validatedData['berat_badan'];
        $persenLukaBakar = $validatedData['persen_luka_bakar'];
        $cairan = 4 * $beratBadan * $persenLukaBakar;

        // Simpan rekam luka bakar
        $rekam = new RekamLukaBakar();
        $rekam->no_rekam = $newNoRekam;
        $rekam->pasien_id = $pasien->id;
        $rekam->tgl_rekam = $validatedData['tgl_rekam'];
        $rekam->berat_badan = $beratBadan;
        $rekam->persen_luka_bakar = $persenLukaBakar;
        $rekam->cairan = $cairan;
        $rekam->save();

        // Ambil data lengkap untuk respons
        $rekamData = RekamLukaBakar::with('pasien')->find($rekam->id);

        return response()->json([
            'message' => 'Rekam luka bakar berhasil disimpan',
            'data' => [
                'no_rekam' => $rekamData->no_rekam,
                'tgl_rekam' => $rekamData->tgl_rekam,
                'berat_badan' => $rekamData->berat_badan,
                'persen_luka_bakar' => $rekamData->persen_luka_bakar,
                'cairan' => $rekamData->cairan,
                'pasien' => [
                    'nama' => $rekamData->pasien->nama,
                    'no_rm' => $rekamData->pasien->no_rm,
                    'alamat_lengkap' => $rekamData->pasien->alamat_lengkap,
                    'no_hp' => $rekamData->pasien->no_hp,
                ],
            ]
        ], 201);
    }
}
