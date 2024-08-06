@extends("layout.apps")
@section("content")
    <div class="form-head align-items-center d-flex mb-sm-4 mb-3">
        <div class="mr-auto">
            <h2 class="text-black font-w600">Tambah Pasien</h2>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ Route("pasien") }}">Data Pasien</a></li>
                <li class="breadcrumb-item active"><a href="#">Tambah Data Pasien</a></li>
            </ol>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body">
                    <div class="basic-form">
                        <form action="{{ Route("pasien.store") }}" method="POST" enctype="multipart/form-data">
                            {{ csrf_field() }}

                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Nama Pasien*</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="nama" id="nama" required
                                        value="{{ old("nama") }}">
                                    @error("nama")
                                        <div class="invalid-feedback animated fadeInUp" style="display: block;">
                                            {{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Tempat Lahir</label>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control" name="tmp_lahir"
                                        value="{{ old("tmp_lahir") }}">
                                    @error("tmp_lahir")
                                        <div class="invalid-feedback animated fadeInUp" style="display: block;">
                                            {{ $message }}</div>
                                    @enderror
                                </div>
                                <label class="col-sm-2 col-form-label">Tanggal Lahir</label>
                                <div class="col-sm-4">
                                    <input type="date" class="form-control" name="tgl_lahir"
                                        value="{{ old("tgl_lahir") }}">
                                    @error("tgl_lahir")
                                        <div class="invalid-feedback animated fadeInUp" style="display: block;">
                                            {{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Jenis Kelamin*</label>
                                <div class="col-sm-4">
                                    <div class="form-check">
                                        <input type="radio" name="jk" class="form-check-input" value="Laki-Laki">
                                        <label class="form-check-label">Laki-Laki</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="radio" name="jk" class="form-check-input" value="Perempuan">
                                        <label class="form-check-label">Perempuan</label>
                                    </div>
                                    @error("jk")
                                        <div class="invalid-feedback animated fadeInUp" style="display: block;">
                                            {{ $message }}</div>
                                    @enderror
                                </div>
                                <label class="col-sm-2 col-form-label">Status Menikah</label>
                                <div class="col-sm-4">
                                    <select name="status_menikah" class="form-control" required>
                                        <option value="">--Pilih--</option>
                                        <option value="Belum Menikah">Belum Menikah</option>
                                        <option value="Menikah">Menikah</option>
                                        <option value="Duda">Duda</option>
                                        <option value="Janda">Janda</option>
                                    </select>
                                    @error("status_menikah")
                                        <div class="invalid-feedback animated fadeInUp" style="display: block;">
                                            {{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Agama</label>
                                <div class="col-sm-4">
                                    <select name="agama" class="form-control">
                                        <option value="">--Pilih--</option>
                                        <option value="Islam">Islam</option>
                                        <option value="Kristen">Kristen</option>
                                        <option value="Katholik">Katholik</option>
                                        <option value="Hindu">Hindu</option>
                                        <option value="Budha">Budha</option>
                                        <option value="Konghucu">Konghucu</option>
                                    </select>
                                    @error("agama")
                                        <div class="invalid-feedback animated fadeInUp" style="display: block;">
                                            {{ $message }}</div>
                                    @enderror
                                </div>
                                <label class="col-sm-2 col-form-label">Pendidikan</label>
                                <div class="col-sm-4">
                                    <select name="pendidikan" class="form-control">
                                        <option value="">--Pilih--</option>
                                        <option value="SD">SD</option>
                                        <option value="SMP">SMP</option>
                                        <option value="SMA">SMA</option>
                                        <option value="Diploma">Diploma</option>
                                        <option value="S1">S1</option>
                                        <option value="S2">S2</option>
                                        <option value="S3">S3</option>
                                        <option value="Tidak Sekolah">Tidak Sekolah</option>
                                    </select>
                                    @error("pendidikan")
                                        <div class="invalid-feedback animated fadeInUp" style="display: block;">
                                            {{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Pekerjaan</label>
                                <div class="col-sm-4">
                                    <select name="pekerjaan" class="form-control">
                                        <option value="">--Pilih--</option>
                                        <option value="PNS">PNS</option>
                                        <option value="Wiraswasta">Wiraswasta</option>
                                        <option value="TNI/Polri">TNI/Polri</option>
                                        <option value="Pelajar/Mahasiswa">Pelajar/Mahasiswa</option>
                                        <option value="Petani">Petani</option>
                                        <option value="Guru/Pengajar">Guru/Pengajar</option>
                                        <option value="IRT">IRT</option>
                                        <option value="Lain-Lain">Lain-Lain</option>
                                    </select>
                                    @error("pekerjaan")
                                        <div class="invalid-feedback animated fadeInUp" style="display: block;">
                                            {{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Alamat Lengkap</label>
                                <div class="col-sm-10">
                                    <textarea name="alamat_lengkap" class="form-control" rows="4">{{ old("alamat_lengkap") }}</textarea>
                                    @error("alamat_lengkap")
                                        <div class="invalid-feedback animated fadeInUp" style="display: block;">
                                            {{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Kelurahan</label>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control" name="kelurahan"
                                        value="{{ old("kelurahan") }}">
                                    @error("kelurahan")
                                        <div class="invalid-feedback animated fadeInUp" style="display: block;">
                                            {{ $message }}</div>
                                    @enderror
                                </div>
                                <label class="col-sm-2 col-form-label">Kecamatan</label>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control" name="kecamatan"
                                        value="{{ old("kecamatan") }}">
                                    @error("kecamatan")
                                        <div class="invalid-feedback animated fadeInUp" style="display: block;">
                                            {{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Kabupaten</label>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control" name="kabupaten"
                                        value="{{ old("kabupaten") }}">
                                    @error("kabupaten")
                                        <div class="invalid-feedback animated fadeInUp" style="display: block;">
                                            {{ $message }}</div>
                                    @enderror
                                </div>
                                <label class="col-sm-2 col-form-label">Kodepos</label>
                                <div class="col-sm-4">
                                    <input type="number" maxlength="5" class="form-control" name="kodepos"
                                        value="{{ old("kodepos") }}">
                                    @error("kodepos")
                                        <div class="invalid-feedback animated fadeInUp" style="display: block;">
                                            {{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">No HP*</label>
                                <div class="col-sm-4">
                                    <input type="number" class="form-control" name="no_hp" required
                                        value="{{ old("no_hp") }}">
                                    @error("no_hp")
                                        <div class="invalid-feedback animated fadeInUp" style="display: block;">
                                            {{ $message }}</div>
                                    @enderror
                                </div>
                                <label class="col-sm-2 col-form-label">Kewarganegaraan</label>
                                <div class="col-sm-4">
                                    <select name="kewarganegaraan" class="form-control">
                                        <option value="">--Pilih--</option>
                                        <option value="WNI">WNI</option>
                                        <option value="WNA">WNA</option>
                                    </select>
                                    @error("kewarganegaraan")
                                        <div class="invalid-feedback animated fadeInUp" style="display: block;">
                                            {{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-10">
                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                    <button type="reset" class="btn btn-light">Batal</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
