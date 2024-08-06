<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pasien;
use Illuminate\Http\Request;

class PasienController extends Controller
{
    // Mendapatkan semua pasien
    public function index()
    {
        $pasien = Pasien::all(); // Atau gunakan paginate jika data banyak
        return response()->json($pasien);
    }

    // Mendapatkan pasien berdasarkan no_rm
    public function showByNoRm($no_rm)
    {
        $pasien = Pasien::where('no_rm', $no_rm)->first();

        if ($pasien) {
            return response()->json($pasien);
        } else {
            return response()->json(['message' => 'Pasien tidak ditemukan'], 404);
        }
    }
}
