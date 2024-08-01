<?php

namespace App\Http\Controllers;

use App\Events\StatusRekamUpdate;
use App\Models\Dokter;
use App\Models\Pasien;
use App\Models\RekamLukaBakar;
use App\Notifications\RekamUpdateNotification;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class RekamLukaBakarController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $role = $user->role_display();
        $rekams = RekamLukaBakar::latest()
            ->select('rekam_luka_bakar.*')
            ->leftJoin('pasien', function ($join) {
                $join->on('rekam_luka_bakar.pasien_id', '=', 'pasien.id');
            })
            ->when($request->keyword, function ($query) use ($request) {
                $query->where('rekam_luka_bakar.tgl_rekam', 'LIKE', "%{$request->keyword}%")
                    ->orWhere('rekam_luka_bakar.cara_bayar', 'LIKE', "%{$request->keyword}%")
                    ->orWhere('pasien.nama', 'LIKE', "%{$request->keyword}%")
                    ->orWhere('pasien.no_bpjs', 'LIKE', "%{$request->keyword}%")
                    ->orWhere('pasien.no_rm', 'LIKE', "%{$request->keyword}%");
            })
            ->paginate(10);
        return view('lukabakar.index', compact('rekams'));
    }

    public function add(Request $request)
    {
        return view('lukabakar.add');
    }

    public function edit(Request $request, $id)
    {
        $data = RekamLukaBakar::find($id);
        return view('lukabakar.edit', compact('data'));
    }

    public function detail(Request $request, $pasien_id)
    {
        $pasien = Pasien::find($pasien_id);

        $rekamLatest = RekamLukaBakar::latest()
            ->where('status', '!=', 5)
            ->where('pasien_id', $pasien_id)
            ->first();

        $rekams = RekamLukaBakar::latest()
            ->where('pasien_id', $pasien_id)
            ->when($request->keyword, function ($query) use ($request) {
                $query->where('tgl_rekam', 'LIKE', "%{$request->keyword}%");
            })
            ->paginate(5);

        if ($rekamLatest) {
            auth()->user()->notifications->where('data.no_rekam', $rekamLatest->no_rekam)->markAsRead();
        }

        return view('lukabakar.detail', compact('pasien', 'rekams', 'rekamLatest'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'tgl_rekam' => 'required',
            'pasien_id' => 'required',
            'berat_badan' => 'required|numeric',
            'persen_luka_bakar' => 'required|numeric',
        ]);

        $pasien = Pasien::where('id', $request->pasien_id)->first();
        if (!$pasien) {
            return redirect()->back()->withInput($request->input())
                ->withErrors(['pasien_id' => 'Data Pasien Tidak Ditemukan']);
        }

        $rekamAda = RekamLukaBakar::where('pasien_id', $request->pasien_id)
            ->whereIn('status', [1, 2, 3, 4])
            ->first();
        if ($rekamAda) {
            return redirect()->back()->withInput($request->input())
                ->withErrors(['pasien_id' => 'Pasien ini masih belum selesai periksa, harap selesaikan pemeriksaan sebelumnya']);
        }

        $cairan = 4 * $request->berat_badan * $request->persen_luka_bakar;

        $request->merge([
            'no_rekam' => "LUKA#" . date('Ymd') . $request->pasien_id,
            'cairan' => $cairan,
            'petugas_id' => auth()->user()->id
        ]);

        RekamLukaBakar::create($request->all());

        return redirect()->route('rekam', $request->pasien_id)
            ->with('sukses', 'Pendaftaran Berhasil, Silakan lakukan pemeriksaan dan teruskan ke dokter terkait');
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'tgl_rekam' => 'required',
            'pasien_id' => 'required',
            'dokter_id' => 'required',
            'berat_badan' => 'required|numeric',
            'persen_luka_bakar' => 'required|numeric',
        ]);

        $pasien = Pasien::where('id', $request->pasien_id)->first();
        if (!$pasien) {
            return redirect()->back()->withInput($request->input())
                ->withErrors(['pasien_id' => 'Data Pasien Tidak Ditemukan']);
        }

        $cairan = 4 * $request->berat_badan * $request->persen_luka_bakar;

        $request->merge([
            'cairan' => $cairan,
        ]);

        $rekam = RekamLukaBakar::find($id);
        $rekam->update($request->all());

        return redirect()->route('lukabakar.detail', $request->pasien_id)
            ->with('sukses', 'Berhasil diperbaharui, Silakan lakukan pemeriksaan dan teruskan ke dokter terkait');
    }

    public function rekam_status(Request $request, $id, $status)
    {
        $rekam = RekamLukaBakar::find($id);

        if ($status == 2 && $rekam->pemeriksaan == null) {
            return redirect()->route('rekam_luka_bakar.detail', $rekam->pasien_id)
                ->with('gagal', 'Pemeriksaan Isi lebih dulu');
        }
        if ($status == 3 && $rekam->tindakan == null) {
            return redirect()->route('rekam_luka_bakar.detail', $rekam->pasien_id)
                ->with('gagal', 'Tindakan dan Diagnosa Belum diisi');
        }

        $rekam->update(['status' => $status]);

        $waktu = Carbon::parse($rekam->created_at)->format('d/m/Y H:i:s');
        if ($status == 2) {
            $dokter = Dokter::find($rekam->dokter_id);
            $user = User::find($dokter->user_id);
            $message = "Pasien " . $rekam->pasien->nama . ", silahkan diproses";
            Notification::send($user, new RekamUpdateNotification($rekam, $message));
            $link = route('lukabakar.detail', $rekam->pasien_id);
            event(new StatusRekamUpdate($user->id, $rekam->no_rekam, $message, $waktu, $link));
        }
        return redirect()->route('lukabakar.detail', $rekam->pasien_id)
            ->with('sukses', 'Status Berhasil di Ubah');
    }

    public function destroy(Request $request, $id)
    {
        $rekam = RekamLukaBakar::find($id);
        $rekam->update([
            'status' => 5,
            'deleted_at' => Carbon::now()
        ]);
        return redirect()->route('lukabakar.detail', $rekam->pasien_id)
            ->with('sukses', 'Rekam medis berhasil dihapus');
    }
}
