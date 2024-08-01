@extends("layout.apps")
@section("content")
    <div class="form-head align-items-center d-flex mb-sm-4 mb-3">
        <div class="mr-auto">
            <h2 class="text-black font-w600">Rekam Medis Baru</h2>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ Route("pasien") }}">Rekam Medis</a></li>
                <li class="breadcrumb-item active"><a href="#">Tambah Pasien Periksa</a></li>
            </ol>
        </div>
    </div>

    <!-- Pencarian Pasien -->
    <div class="modal fade" id="modalPasien">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Data Pasien</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive card-table">
                        <table class="display white-border table-responsive-sm" style="width: 100%" id="pasien-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>No. RM</th>
                                    <th>Nama Pasien</th>
                                    <th>Tgl Lahir</th>
                                    <th>No. HP</th>
                                </tr>
                            </thead>

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body">
                    <div class="basic-form">
                        <form action="{{ Route("rekam.store") }}" method="POST">
                            {{ csrf_field() }}
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Tanggal Periksa*</label>
                                <div class="col-sm-4">
                                    <input type="date" name="tgl_rekam" class="form-control" value="{{ date("Y-m-d") }}">
                                    @error("tgl_rekam")
                                        <div class="invalid-feedback animated fadeInUp" style="display: block;">
                                            {{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Nama Pasien*</label>
                                <div class="col-sm-5 ">
                                    <input type="hidden" class="form-control " id="pasien_id" name="pasien_id"
                                        value="{{ old("pasien_id") }}">
                                    <div class="input-group transparent-append">
                                        <input type="text" id="pasien_nama" class="form-control" data-toggle="modal"
                                            data-target="#modalPasien"
                                            value="{{ old("pasien_nama") ? old("pasien_nama") : "" }}" name="pasien_nama"
                                            placeholder="Pilih Pasien..">
                                        <div class="input-group-append show-pass" data-toggle="modal"
                                            data-target="#modalPasien">
                                            <span class="input-group-text">
                                                <a href="javascript:void(0)" data-toggle="modal"
                                                    data-target="#modalPasien"><i class="fa fa-search"></i></a>
                                            </span>
                                        </div>
                                    </div>
                                    @error("pasien_id")
                                        <div class="invalid-feedback animated fadeInUp" style="display: block;">
                                            {{ $message }}</div>
                                    @enderror
                                </div>

                            </div>

                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Berat Badan*</label>
                                <div class="col-sm-10">
                                    <input type="text" name="berat_badan" required class="form-control"
                                        value="{{ old("berat_badan") }}">
                                    @error("berat_badan")
                                        <div class="invalid-feedback animated fadeInUp" style="display: block;">
                                            {{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Persentase Luka Bakar*</label>
                                <div class="col-sm-10">
                                    <input type="text" name="persen_luka_bakar" required class="form-control"
                                        value="{{ old("persen_luka_bakar") }}">
                                    @error("persen_luka_bakar")
                                        <div class="invalid-feedback animated fadeInUp" style="display: block;">
                                            {{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <hr>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">SIMPAN</button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section("script")
    <script>
        $(function() {
            var table = $('#pasien-table').DataTable({
                processing: true,
                serverSide: true,
                searching: true,
                paging: true,
                select: false,
                pageLength: 5,
                lengthChange: false,
                ajax: "{{ route("pasien.json") }}",
                columns: [{
                        data: 'action',
                        name: 'action'
                    },
                    {
                        data: 'no_rm',
                        name: 'no_rm'
                    },
                    {
                        data: 'nama',
                        name: 'nama'
                    },
                    {
                        data: 'tgl_lahir',
                        name: 'tgl_lahir'
                    },
                    {
                        data: 'no_hp',
                        name: 'no_hp'
                    },
                ]
            });

        });
        $(document).on("click", ".pilihPasien", function() {
            var id = $(this).data('id');
            var nama = $(this).data('nama');
            var no = $(this).data('no');
            var metode = $(this).data('metode');
            $("#pasien_nama").val(nama);
            $("#pasien_id").val(id);

            $("#modalPasien").modal('hide');

            toastr.success("Pasien " + nama + " telah dipilih", "Sukses", {
                timeOut: 3000
            })
        });
    </script>
@endsection
