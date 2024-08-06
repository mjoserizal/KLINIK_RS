@extends("layout.apps")
@section("content")
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body">
                    <ul class="nav nav-tabs">
                        <div class="form-group col-lg-6" style="float: left">
                            <a href="{{ Route("rekam.add") }}" class="btn btn-primary mr-3">+Rekam Luka Bakar</a>
                        </div>
                        <div class="form-group col-lg-6" style="float: right">
                            <form method="get" action="{{ url()->current() }}">
                                <div class="input-group">
                                    <input type="text" class="form-control gp-search" name="keyword"
                                        value="{{ request("keyword") }}" placeholder="Cari" value=""
                                        autocomplete="off">
                                    <div class="input-group-btn">
                                        <button type="submit" class="btn btn-default no-border btn-sm gp-search">
                                            <i class="ace-icon fa fa-search icon-on-right bigger-110"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>

                        </div>

                        @if (auth()->user()->role_display() == "Dokter")
                            <li class="nav-item">
                                <a href="{{ Route("rekam", ["tab" => 2]) }}"
                                    class="nav-link {{ Request("tab") == 2 ? "active" : "" }}">
                                    <i class="la la-user mr-2"></i> Perlu Diperiksa</a>
                            </li>
                            <li class="nav-item ">
                                <a href="{{ Route("rekam", ["tab" => 5]) }}"
                                    class="nav-link {{ Request("tab") == 5 ? "active" : "" }}">
                                    <i class="la la-envelope mr-2"></i> Selesai Diperiksa</a>
                            </li>
                        @endif

                    </ul>

                    <div class="table-responsive card-table">
                        <table class="table table-responsive-md">
                            <thead>
                                <tr>

                                    <th>No</th>
                                    <th>Tanggal</th>
                                    <th>Nama Pasien</th>
                                    <th>Berat Badan </th>
                                    <th>% Luka Bakar</th>
                                    <th>Cairan</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($rekams as $key => $row)
                                    <tr>
                                        <td align="center">{{ $rekams->firstItem() + $key }}</td>
                                        <td>{{ $row->no_rekam }}<br />{{ $row->tgl_rekam }}</td>
                                        <td>
                                            <a href="{{ Route("rekam.detail", $row->pasien_id) }}">
                                                {{ $row->pasien_nama ?: "Pasien tidak ditemukan" }}
                                            </a>
                                        </td>
                                        <td>{{ $row->berat_badan }} kg</td>
                                        <td>{{ $row->persen_luka_bakar }}%</td>
                                        <td>{{ $row->cairan }} ml</td>
                                        <td>{!! $row->status_display() !!}</td>
                                        <td>
                                            <div class="d-flex">
                                                <a href="{{ Route("rekam.detail", $row->pasien_id) }}"
                                                    class="btn btn-primary shadow btn-xs sharp mr-1">
                                                    <i class="fa fa-user-md"></i>
                                                </a>
                                                @if (auth()->user()->role_display() == "Admin" && $row->status == 2)
                                                    <a href="{{ Route("rekam.edit", $row->id) }}"
                                                        class="btn btn-info shadow btn-xs sharp mr-1">
                                                        <i class="fa fa-pencil"></i>
                                                    </a>
                                                    <a href="#" class="btn btn-danger shadow btn-xs sharp delete"
                                                        r-link="{{ Route("rekam.delete", $row->id) }}"
                                                        r-name="{{ $row->pasien_nama }}" r-id="{{ $row->id }}">
                                                        <i class="fa fa-trash"></i>
                                                    </a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>

                        </table>
                        {{ $rekams->appends(request()->except("page"))->links() }}

                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

@section("script")
    <script>
        $().ready(function() {
            $(".delete").click(function() {
                var id = $(this).attr('r-id');
                var name = $(this).attr('r-name');
                var link = $(this).attr('r-link');

                Swal.fire({
                    title: 'Ingin Menghapus?',
                    text: "Yakin ingin menghapus data  : " + name + " ini ?",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, hapus !'
                }).then((result) => {
                    console.log(result);
                    if (result.value) {
                        window.location = link;
                    }
                });
            });
        });
    </script>
@endsection
