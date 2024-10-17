@extends('Layout.Admin.main')

@section('main')

    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">{{ $title }}</h1>
        <p class="my-4">Lorem ipsum dolor sit amet consectetur, adipisicing elit.</p>
        <div class="my-4">
            <button data-toggle="modal" data-target="#tambahPasien" class="btn btn-primary btn-icon-split">
                <span class="icon text-white-50">
                    <i class="fas fa-plus"></i>
                </span>
                <span class="text">Tambah</span>
            </button>
            
            <button data-toggle="modal" data-target="#filterPasien" class="btn btn-secondary btn-icon-split">
                <span class="icon text-white-50">
                    <i class="fas fa-filter"></i>
                </span>
                <span class="text">Filter</span>
            </button>
            
            <button data-toggle="modal" data-target="#exportPasien" class="btn btn-success btn-icon-split">
                <span class="icon text-white-50">
                    <i class="fas fa-file-export"></i>
                </span>
                <span class="text">Export</span>
            </button>
        </div>

        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <div class="d-flex justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Tabel {{ $title }}</h6>
                    
                    <form action="{{ route('pasien.search') }}" method="GET">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control bg-light border-1 small" placeholder="Search for..."
                                aria-label="Search" aria-describedby="basic-addon2">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="submit">
                                    <i class="fas fa-search fa-sm"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>NO KTP</th>
                                <th>Nama</th>
                                <th>Tempat Lahir</th>
                                <th>Tanggal Lahir</th>
                                <th>Jenis Kelamin</th>
                                <th>Alamat</th>
                                <th>RT</th>
                                <th>RW</th>
                                <th>Desa</th>
                                <th>Kecamatan</th>
                                <th>Pekerjaan</th>
                                <th>Tanggal Di Buat</th>                           
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data as $item)
                            <tr>
                                <th scope="row">{{ $loop->iteration }}</th>
                                <td>{{ $item->no_ktp }}</td>
                                <td>{{ $item->nama }}</td>
                                <td>{{ $item->tempat_lahir }}</td>
                                <td>{{ $item->tanggal_lahir }}</td>
                                <td>{{ $item->jenis_kelamin }}</td>
                                <td>{{ $item->alamat }}</td>
                                <td>{{ $item->rt }}</td>
                                <td>{{ $item->rw }}</td>
                                <td>{{ $item->desa }}</td>
                                <td>{{ $item->kecamatan }}</td>
                                <td>{{ $item->pekerjaan }}</td>
                                <td>{{ $item->created_at }}</td>
                                <td>
                                    @if(auth()->user()->status == 1)
                                    <button 
                                        href="javascript:;" 
                                        data-toggle="modal" 
                                        data-target="#editPasien{{ $item->id }}"  
                                        class="btn btn-warning btn-circle btn-sm">
                                        <i class="fas fa-exclamation-triangle"></i>
                                    </button>
                                    <button 
                                        href="javascript:;" 
                                        data-toggle="modal" 
                                        data-target="#hapusPasien{{ $item->id }}"  
                                        class="btn btn-danger btn-circle btn-sm">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        {!! $data->appends(request()->query())->links() !!}
    </div>
    <!-- /.container-fluid -->

    <!-- Modal Tambah -->
    <div class="modal fade" id="tambahPasien" tabindex="-1" aria-labelledby="tambahPasienLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form class="modal-content" method="POST" action="{{ url('pasien') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="tambahPasienLabel">Modal Tambah {{ $title }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="no_ktp">No KTP</label>
                        <input type="text" name="no_ktp" class="form-control" id="no_ktp">
                    </div>
                    <div class="form-group">
                        <label for="nama">Nama</label>
                        <input type="text" name="nama" class="form-control" id="nama">
                    </div>
                    <div class="form-group">
                        <label for="tempat_lahir">Tempat Lahir</label>
                        <input type="text" name="tempat_lahir" class="form-control" id="tempat_lahir">
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="tanggal_lahir">Tanggal Lahir</label>
                            <input type="date" name="tanggal_lahir" class="form-control" id="tanggal_lahir">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="jenis_kelamin">Jenis Kelamin</label>
                            <select id="jenis_kelamin" name="jenis_kelamin" class="form-control">
                                <option value="1">Laki Laki</option>
                                <option value="0">Perampuan</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="alamat">Alamat</label>
                        <input type="text" name="alamat" class="form-control" id="alamat">
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="rt">RT</label>
                            <input type="text" name="rt" class="form-control" id="rt">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="rw">RW</label>
                            <input type="text" name="rw" class="form-control" id="rw">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="desa">Desa</label>
                            <input type="text" name="desa" class="form-control" id="desa">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="kecamatan">Kecamatan</label>
                            <input type="text" name="kecamatan" class="form-control" id="kecamatan">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="pekerjaan">Pekerjaan</label>
                            <input type="text" name="pekerjaan" class="form-control" id="pekerjaan">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Filter -->
    <div class="modal fade" id="filterPasien" tabindex="-1" aria-labelledby="filterPasienLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form class="modal-content" method="GET" action="{{ url('pasien/filter') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="filterPasienLabel">Modal Filter {{ $title }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-row">
                        <div class="col">
                            <label for="tanggal_awal">Tanggal Awal</label>
                            <input type="date" name="tanggal_awal" id="tanggal_awal" class="form-control" placeholder="Menu" required>
                        </div>
                        <div class="col">
                            <label for="tanggal_akhir">Tanggal Akhir</label>
                            <input type="date" name="tanggal_akhir" id="tanggal_akhir" class="form-control" placeholder="Menu" required>
                        </div>
                    </div>
                </div> <!-- Added closing div for modal-body -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Export -->
    <div class="modal fade" id="exportPasien" tabindex="-1" aria-labelledby="exportPasienLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form class="modal-content" method="GET" action="{{ url('pasien/export') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="exportPasienLabel">Modal Export {{ $title }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-row">
                        <div class="col">
                            <label for="tanggal_awal">Tanggal Awal</label>
                            <input type="date" name="tanggal_awal" id="tanggal_awal" class="form-control" placeholder="Menu" required>
                        </div>
                        <div class="col">
                            <label for="tanggal_akhir">Tanggal Akhir</label>
                            <input type="date" name="tanggal_akhir" id="tanggal_akhir" class="form-control" placeholder="Menu" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Edit -->
    @foreach($data as $item)
    <div class="modal fade" id="editPasien{{ $item->id }}" tabindex="-1" aria-labelledby="editPasien{{ $item->id }}}Label" aria-hidden="true">
        <div class="modal-dialog">
            <form class="modal-content" method="POST" action="{{ url('pasien/update/' . $item->id) }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="editPasien{{ $item->id }}Label">Modal Edit {{ $title }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="no_ktp">No KTP</label>
                        <input type="text" value="{{ $item->no_ktp }}" name="no_ktp" class="form-control" id="no_ktp">
                    </div>
                    <div class="form-group">
                        <label for="nama">Nama</label>
                        <input type="text" value="{{ $item->nama }}" name="nama" class="form-control" id="nama">
                    </div>
                    <div class="form-group">
                        <label for="tempat_lahir">Tempat Lahir</label>
                        <input type="text" value="{{ $item->tempat_lahir }}" name="tempat_lahir" class="form-control" id="tempat_lahir">
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="tanggal_lahir">Tanggal Lahir</label>
                            <input type="date" value="{{ $item->tanggal_lahir }}" name="tanggal_lahir" class="form-control" id="tanggal_lahir">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="jenis_kelamin">Jenis Kelamin</label>
                            <select id="jenis_kelamin" name="jenis_kelamin" class="form-control">
                                <option value="1">Laki Laki</option>
                                <option value="0">Perampuan</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="alamat">Alamat</label>
                        <input type="text" value="{{ $item->alamat }}" name="alamat" class="form-control" id="alamat">
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="rt">RT</label>
                            <input type="text" value="{{ $item->rt }}" name="rt" class="form-control" id="rt">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="rw">RW</label>
                            <input type="text" value="{{ $item->rw }}" name="rw" class="form-control" id="rw">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="desa">Desa</label>
                            <input type="text" value="{{ $item->desa }}" name="desa" class="form-control" id="desa">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="kecamatan">Kecamatan</label>
                            <input type="text" value="{{ $item->kecamatan }}" name="kecamatan" class="form-control" id="kecamatan">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="pekerjaan">Pekerjaan</label>
                            <input type="text" value="{{ $item->pekerjaan }}" name="pekerjaan" class="form-control" id="pekerjaan">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
    @endforeach

    @foreach($data as $item)
    <!-- Modal -->
    <div class="modal fade" id="hapusPasien{{ $item->id }}" tabindex="-1" aria-labelledby="hapusPasien{{ $item->id }}Label" aria-hidden="true">
        <div class="modal-dialog">
        <form method="POST" action="{{ url('pasien/destroy/' . $item->id) }}" class="modal-content">
            @csrf
            <div class="modal-header">
            <h5 class="modal-title" id="hapusPasien{{ $item->id }}Label">Modal title</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <div class="modal-body">
                Apakah anda ingin menghapus data ini <h3>{{ $item->nama }}</h3>
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-danger">Delete changes</button>
            </div>
        </form>
        </div>
    </div>
    @endforeach


@endsection