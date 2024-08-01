<?php

namespace App\Http\Controllers;

use App\Models\Dokter;
use App\Models\Poli;
use App\Models\Rekam;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DokterController extends Controller
{
    public function index(Request $request)
    {
        $datas = Dokter::with('user')->get();
        $poli = Poli::all();
        return view('dokter.index', compact('datas', 'poli'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nip' => 'required',
            'nama' => 'required',
            'poli' => 'required',
            'no_hp' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'alamat' => 'nullable',
        ]);

        $user = User::create([
            'name' => $request->nama,
            'phone' => $request->no_hp,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => 3, // Or whatever role you want to assign
            'status' => 1, // Or whatever default status you want to assign
        ]);

        Dokter::create([
            'nip' => $request->nip,
            'nama' => $request->nama,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
            'poli' => $request->poli,
            'status' => 1, // Or whatever default status you want to assign
            'user_id' => $user->id,
        ]);

        return redirect()->back()->with('success', 'Dokter berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nip' => 'required',
            'nama' => 'required',
            'poli' => 'required',
            'no_hp' => 'required',
            'email' => 'required|email|unique:users,email,' . $request->user_id,
            'password' => 'nullable|min:6',
            'alamat' => 'nullable',
        ]);

        $dokter = Dokter::findOrFail($id);
        $user = User::findOrFail($dokter->user_id);

        $user->update([
            'name' => $request->nama,
            'phone' => $request->no_hp,
            'email' => $request->email,
            'password' => $request->password ? bcrypt($request->password) : $user->password,
            'role' => 3, // Or whatever role you want to assign
            'status' => 1, // Or whatever default status you want to assign
        ]);

        $dokter->update([
            'nip' => $request->nip,
            'nama' => $request->nama,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
            'poli' => $request->poli,
            'status' => 1, // Or whatever default status you want to assign
        ]);

        return redirect()->back()->with('success', 'Dokter berhasil diperbarui');
    }

    public function delete(Request $request, $id)
    {
        $rekam = Rekam::where('dokter_id', $id)->count();
        if ($rekam >= 1) {
            $dokter = Dokter::find($id);
            $dokter->update([
                'status' => 0
            ]);
            User::find($dokter->user_id)->update([
                'status' => 0
            ]);
            return redirect()->route('dokter')->with('sukses', 'Data dokter di non aktifkan');
        } else {
            $dokter = Dokter::find($id);
            $dokter->delete();
            User::find($dokter->user_id)->delete();
        }
        return redirect()->route('dokter')->with('sukses', 'Data berhasil dihapus');
    }

    public function getDokter(Request $request)
    {
        $data = Dokter::select('id', 'nama')->where('status', 1)->get();
        if ($poli = $request->get('poli'))
            $data = Dokter::select('id', 'nama')
                ->where('status', 1)
                ->where('poli', $poli)->get();

        return response()->json(['success' => true, 'data' => $data], 200);
    }

    public function updatepassword(Request $request, $id)
    {
        $this->validate($request, [
            'password' => 'required|min:6',
            'password_konfirm' => 'required_with:password|same:password|min:6'
        ]);

        $password = bcrypt($request->password);
        User::where('id', $id)->update([
            'password' => $password,
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
        return redirect()->route('dokter')->with('sukses', 'Selamat, password anda sudah diperbaharui');
    }
}
