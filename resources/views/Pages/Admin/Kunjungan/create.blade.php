@extends('Layout.Admin.main')

@section('main')

    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">{{ $title }}</h1>
        <p class="my-4">Lorem ipsum dolor sit amet consectetur, adipisicing elit.</p>

        <div class="row">

            <!-- Area Chart -->
            <div class="col-xl-8 col-lg-7">
                <div class="card shadow mb-4">
                    <!-- Card Header - Dropdown -->
                    <div
                        class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Form {{ $title }}</h6>
                    </div>
                    <!-- Card Body -->
                    <div class="card-body">
                        <form method="POST" action="{{ url('kunjungan') }}">
                            @csrf
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="no_ktp">No KTP</label>
                                    <input type="text" class="form-control" id="no_ktp" name="no_ktp" value="{{ isset($pasien) ? $pasien->no_ktp : '' }}" {{ isset($pasien) ? 'readonly' : '' }}>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="nama">Nama Pasien</label>
                                    <input type="text" class="form-control" id="nama" name="nama" value="{{ isset($pasien) ? $pasien->nama : '' }}" {{ isset($pasien) ? 'readonly' : '' }}>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="tempat_lahir">Tempat Lahir</label>
                                    <input type="text" class="form-control" id="tempat_lahir" name="tempat_lahir" value="{{ isset($pasien) ? $pasien->tempat_lahir : '' }}" {{ isset($pasien) ? 'readonly' : '' }}>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="tanggal_lahir">Tanggal Lahir</label>
                                    <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir" value="{{ isset($pasien) ? $pasien->tanggal_lahir : '' }}" {{ isset($pasien) ? 'readonly' : '' }}>
                                </div>
                                <div class="form-group col-md-2">
                                    <label for="jenis_kelamin">Jenis Kelamin</label>
                                    <select id="jenis_kelamin" name="jenis_kelamin" class="form-control" {{ isset($pasien) ? 'readonly' : '' }}>
                                        <option value="1" {{ isset($pasien) && $pasien->jenis_kelamin == 1 ? 'selected' : '' }}>Laki Laki</option>
                                        <option value="0" {{ isset($pasien) && $pasien->jenis_kelamin == 0 ? 'selected' : '' }}>Perempuan</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-8">
                                    <label for="alamat">Alamat</label>
                                    <input type="text" class="form-control" id="alamat" name="alamat" value="{{ isset($pasien) ? $pasien->alamat : '' }}" {{ isset($pasien) ? 'readonly' : '' }}>
                                </div>
                                <div class="form-group col-md-2">
                                    <label for="rt">RT</label>
                                    <input type="text" class="form-control" id="rt" name="rt" value="{{ isset($pasien) ? $pasien->rt : '' }}" {{ isset($pasien) ? 'readonly' : '' }}>
                                </div>
                                <div class="form-group col-md-2">
                                    <label for="rw">RW</label>
                                    <input type="text" class="form-control" id="rw" name="rw" value="{{ isset($pasien) ? $pasien->rw : '' }}" {{ isset($pasien) ? 'readonly' : '' }}>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label for="desa">Desa</label>
                                    <input type="text" class="form-control" id="desa" name="desa" value="{{ isset($pasien) ? $pasien->desa : '' }}" {{ isset($pasien) ? 'readonly' : '' }}>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="kecamatan">Kecamatan</label>
                                    <input type="text" class="form-control" id="kecamatan" name="kecamatan" value="{{ isset($pasien) ? $pasien->kecamatan : '' }}" {{ isset($pasien) ? 'readonly' : '' }}>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="pekerjaan">Pekerjaan</label>
                                    <input type="text" class="form-control" id="pekerjaan" name="pekerjaan" value="{{ isset($pasien) ? $pasien->pekerjaan : '' }}" {{ isset($pasien) ? 'readonly' : '' }}>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="dokter_penanggung_jawab">Dokter Penanggung Jawab</label>
                                    <input type="text" class="form-control" id="dokter_penanggung_jawab" name="dokter_penanggung_jawab">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="poli_departemen">Poli Departemen</label>
                                    <select id="poli_departemen" name="poli_departemen" class="form-control">
                                        @foreach ($poli as $item)
                                            <option value="{{ $item->id }}">{{ $item->poli }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="keperluan_kunjungan">Keperluan Kunjungan</label>
                                <input type="text" class="form-control" id="keperluan_kunjungan" name="keperluan_kunjungan">
                            </div>
                            <div class="form-group">
                                <label for="catatan_tambahan">Catatan Tambahan</label>
                                <textarea class="form-control" id="catatan_tambahan" name="catatan_tambahan" rows="3"></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                        
                    </div>
                </div>
            </div>

            <!-- Pie Chart -->
            <div class="col-xl-4 col-lg-5">
                <div class="card shadow mb-4">
                    <!-- Card Header - Dropdown -->
                    <div
                        class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Caution</h6>
                    </div>
                    <!-- Card Body -->
                    <div class="card-body">
                        Lorem ipsum dolor sit amet consectetur adipisicing elit. Ab natus id ipsa eveniet maxime ullam laudantium debitis cupiditate ipsam iusto dolor, eaque culpa quidem. Voluptate placeat sint quibusdam enim similique!
                    </div>
                </div>
            </div>

        </div>

    </div>
    <!-- /.container-fluid -->

@endsection