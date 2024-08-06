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
            ->select('rekam_luka_bakar.*', 'pasien.nama as pasien_nama')
            ->leftJoin('pasien', function ($join) {
                $join->on('rekam_luka_bakar.pasien_id', '=', 'pasien.id');
            })
            ->when($request->keyword, function ($query) use ($request) {
                $query->where('rekam_luka_bakar.tgl_rekam', 'LIKE', "%{$request->keyword}%")
                    ->orWhere('pasien.nama', 'LIKE', "%{$request->keyword}%")
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
            'no_rekam' => "RBK#" . date('Ymd') . $request->pasien_id,
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
        // Cari rekam luka bakar berdasarkan ID
        $rekam = RekamLukaBakar::find($id);

        // Pengecekan apakah rekam luka bakar ditemukan
        if (!$rekam) {
            return redirect()->route('rekam.index')
                ->with('gagal', 'Rekam medis tidak ditemukan.');
        }

        // Langsung mengubah status tanpa pengecekan pemeriksaan dan tindakan
        $rekam->update([
            'status' => $status
        ]);

        $waktu = Carbon::parse($rekam->created_at)->format('d/m/Y H:i:s');
        $link = route('rekam.detail', $rekam->pasien_id);

        // Kirim notifikasi berdasarkan status
        if ($status == 2) {
            $user = User::where('role', 4)->get();
            $message = "Pasien " . $rekam->pasien->nama . ", silahkan diproses";
            Notification::send($user, new RekamUpdateNotification($rekam, $message));
            foreach ($user as $key => $item) {
                event(new StatusRekamUpdate($item->id, $rekam->no_rekam, $message, $link, $waktu));
            }
        } else if ($status == 3) {
            $user = User::where('role', 4)->get();
            $message = "Obat a\n Pasien " . $rekam->pasien->nama . ", silahkan diproses";
            Notification::send($user, new RekamUpdateNotification($rekam, $message));
            foreach ($user as $key => $item) {
                event(new StatusRekamUpdate($item->id, $rekam->no_rekam, $message, $link, $waktu));
            }
        } else if ($status == 4) {
            $user = User::where('role', 2)->get();
            $message = "Pembayaran a\n Pasien " . $rekam->pasien->nama . ", silahkan diproses";
            Notification::send($user, new RekamUpdateNotification($rekam, $message));
            foreach ($user as $key => $item) {
                event(new StatusRekamUpdate($item->id, $rekam->no_rekam, $message, $link, $waktu));
            }
        }

        return redirect()->route('rekam.detail', $rekam->pasien_id)
            ->with('sukses', 'Berhasil diperbaharui');
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
