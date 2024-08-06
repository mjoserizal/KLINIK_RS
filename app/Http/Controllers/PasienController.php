<?php

namespace App\Http\Controllers;

use App\Models\Pasien;
use Carbon\Carbon;
use Illuminate\Http\Request;
use DataTables;
// use Image;
use App\Models\Rekam;
use App\Models\PengeluaranObat;

class PasienController extends Controller
{
    public function json(Request $request)
    {
        // return DataTables::of(Icd::query())->toJson();
        if ($request->ajax()) {
            return DataTables::of(Pasien::query())
                ->addColumn('action', function ($data) {
                    $button = '<a href="javascript:void(0)"
                            data-id="' . $data->id . '"
                            data-nama="' . $data->nama . '"
                            data-no="' . $data->no_rm . '"
                            data-metode="' . $data->cara_bayar . '"
                            class="btn btn-primary shadow btn-xs pilihPasien">
                            Pilih</a>';
                    return $button;
                })->rawColumns(['action'])
                ->toJson();
        }
        return DataTables::of(Pasien::query())
            ->addColumn('action', function ($data) {
                $button = '<a href="javascript:void(0)"
                data-id="' . $data->id . '"
                data-nama="' . $data->nama . '"
                data-no="' . $data->no_rm . '"
                data-metode="' . $data->cara_bayar . '"
                class="btn btn-primary shadow btn-xs pilihPasien">
                Pilih</a>';
                return $button;
            })->rawColumns(['action'])->toJson();
    }

    public function index(Request $request)
    {
        $datas = Pasien::whereNull('deleted_at')
            ->when($request->keyword, function ($query) use ($request) {
                $query->where('no_rm', 'LIKE', "%{$request->keyword}%")
                    ->orWhere('nama', 'LIKE', "%{$request->keyword}%")
                    ->orWhere('no_bpjs', 'LIKE', "%{$request->keyword}%")
                    ->orWhere('no_hp', 'LIKE', "%{$request->keyword}%")
                    ->orWhere('alamat_lengkap', 'LIKE', "%{$request->keyword}%");
            })->paginate(10);
        return view('pasien.index', compact('datas'));
    }

    function add(Request $request)
    {
        return view('pasien.add');
    }

    function edit(Request $request, $id)
    {
        $data = Pasien::find($id);
        return view('pasien.edit', compact('data'));
    }

    function file(Request $request, $id)
    {
        $data = Pasien::find($id);
        return view('pasien.file', compact('data'));
    }

    public function store(Request $request)
    {
        // Validasi data
        $validatedData = $request->validate([
            'nama' => 'required|string|max:255',
            'tmp_lahir' => 'nullable|string|max:255',
            'tgl_lahir' => 'nullable|date',
            'jk' => 'required|string|max:20',
            'status_menikah' => 'nullable|string|max:20',
            'agama' => 'nullable|string|max:50',
            'pendidikan' => 'nullable|string|max:50',
            'pekerjaan' => 'nullable|string|max:50',
            'alamat_lengkap' => 'nullable|string|max:500',
            'kelurahan' => 'nullable|string|max:255',
            'kecamatan' => 'nullable|string|max:255',
            'kabupaten' => 'nullable|string|max:255',
            'kodepos' => 'nullable|integer|max:99999',
            'no_hp' => 'required|string|max:13',
            'kewarganegaraan' => 'nullable|string|max:50'
        ]);

        // Generate no_rm
        $latestPasien = Pasien::orderBy('created_at', 'desc')->first();
        $latestNoRm = $latestPasien ? $latestPasien->no_rm : 'RM00';
        $newNoRm = 'RM' . str_pad((int)substr($latestNoRm, 2) + 1, 3, '0', STR_PAD_LEFT);

        // Simpan data ke database
        $pasien = new Pasien();
        $pasien->no_rm = $newNoRm;
        $pasien->nama = $request->nama;
        $pasien->tmp_lahir = $request->tmp_lahir;
        $pasien->tgl_lahir = $request->tgl_lahir;
        $pasien->jk = $request->jk;
        $pasien->status_menikah = $request->status_menikah;
        $pasien->agama = $request->agama;
        $pasien->pendidikan = $request->pendidikan;
        $pasien->pekerjaan = $request->pekerjaan;
        $pasien->alamat_lengkap = $request->alamat_lengkap;
        $pasien->kelurahan = $request->kelurahan;
        $pasien->kecamatan = $request->kecamatan;
        $pasien->kabupaten = $request->kabupaten;
        $pasien->kodepos = $request->kodepos;
        $pasien->no_hp = $request->no_hp;
        $pasien->kewarganegaraan = $request->kewarganegaraan;
        $pasien->save();

        // Redirect atau kembalikan respon sesuai kebutuhan
        return redirect()->route('pasien')->with('success', 'Data pasien berhasil disimpan.');
    }

    function update(Request $request, $id)
    {
        $this->validate($request, [
            'nama' => 'required',
            'no_hp' => 'required',
            'jk' => 'required',
            'cara_bayar' => 'required',
            'file' => 'mimes:jpg,png,jpeg'
        ]);
        $data = Pasien::find($id);
        $data->update($request->all());
        if ($request->hasFile('file')) {
            $originName = $request->file('file')->getClientOriginalName();
            $fileName = pathinfo($originName, PATHINFO_FILENAME);
            $extension = $request->file('file')->getClientOriginalExtension();
            $fileName = $data->no_rm . '.' . $extension;
            $request->file('file')->move('images/pasien/', $fileName);
            $data->update([
                'general_uncent' => $fileName
            ]);
        }
        return redirect()->route('pasien')->with('sukses', 'Data berhasil diperbaharui');
    }

    function delete(Request $request, $id)
    {
        $pasien = Pasien::find($id);

        if ($pasien) {
            $pasien->delete();

            // Jika Anda masih ingin menghapus data terkait, Anda bisa menghapus kode ini
            // Rekam::where('pasien_id', $id)->delete();
            // PengeluaranObat::where('pasien_id', $id)->delete();
        }

        return redirect()->route('pasien')->with('sukses', 'Data berhasil dihapus');
    }


    function getLastRM(Request $request)
    {
        if ($code = $request->get('code')) {
            $data = Pasien::orderBy('no_rm', 'desc')
                ->where('no_rm', 'LIKE', "%{$code}%")
                ->first();
            if ($data) {
                $last_no = substr($data->no_rm, 2, 3);
                $noLast = (int)$last_no;
                $newNo = $noLast + 1;
                $nomorBaru = $newNo;
                if ($newNo < 10) {
                    $nomorBaru = "00" . $newNo;
                } else if ($newNo < 100) {
                    $nomorBaru = "0" . $newNo;
                }
                $no_rm_baru = $code . $nomorBaru;
                return response()->json([
                    'success' => true,
                    'data' => $no_rm_baru
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                ], 400);
            }
        }

        return response()->json(['success' => false], 400);
    }
}
