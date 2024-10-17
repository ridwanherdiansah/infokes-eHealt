@extends('Layout.Admin.main')

@section('main')

    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">{{ $title }}</h1>
        <p class="my-4">Lorem ipsum dolor sit amet consectetur, adipisicing elit.</p>
        <div class="my-4">
            <button data-toggle="modal" data-target="#tambahMenu" class="btn btn-primary btn-icon-split">
                <span class="icon text-white-50">
                    <i class="fas fa-plus"></i>
                </span>
                <span class="text">Tambah</span>
            </button>
            
            <button data-toggle="modal" data-target="#filterMenu" class="btn btn-secondary btn-icon-split">
                <span class="icon text-white-50">
                    <i class="fas fa-filter"></i>
                </span>
                <span class="text">Filter</span>
            </button>
            
            <button data-toggle="modal" data-target="#exportMenu" class="btn btn-success btn-icon-split">
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
                    
                    <form action="{{ route('menu.search') }}" method="GET">
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
                                <th>Id Menu</th>
                                <th>Nama</th>
                                <th>Tanggal Di Buat</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data as $item)
                            <tr>
                                <th scope="row">{{ $loop->iteration }}</th>
                                <td>{{ $item->id }}</td>
                                <td>{{ $item->menu }}</td>
                                <td>{{ $item->created_at }}</td>
                                <td>
                                    @if(auth()->user()->status == 1)
                                    <button 
                                        href="javascript:;" 
                                        data-toggle="modal" 
                                        data-target="#edit{{ $item->id }}"  
                                        class="btn btn-warning btn-circle btn-sm">
                                        <i class="fas fa-exclamation-triangle"></i>
                                    </button>
                                    <button 
                                        href="javascript:;" 
                                        data-toggle="modal" 
                                        data-target="#hapus{{ $item->id }}"  
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
    <div class="modal fade" id="tambahMenu" tabindex="-1" aria-labelledby="tambahMenuLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form class="modal-content" method="POST" action="{{ url('menu') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="tambahMenuLabel">Modal Tambah {{ $title }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-row">
                        <div class="col">
                            <label for="menu">Menu</label>
                            <input type="text" name="menu" id="menu" class="form-control" placeholder="Menu" required>
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

    <!-- Modal Filter -->
    <div class="modal fade" id="filterMenu" tabindex="-1" aria-labelledby="filterMenuLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form class="modal-content" method="GET" action="{{ url('menu/filter') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="filterMenuLabel">Modal Filter {{ $title }}</h5>
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
    <div class="modal fade" id="exportMenu" tabindex="-1" aria-labelledby="exportMenuLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form class="modal-content" method="GET" action="{{ url('menu/export') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="exportMenuLabel">Modal Export {{ $title }}</h5>
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

    <!-- Modal Edit -->
    @foreach($data as $item)
    <div class="modal fade" id="edit{{ $item->id }}" tabindex="-1" aria-labelledby="edit{{ $item->id }}Label" aria-hidden="true">
        <div class="modal-dialog">
            <form class="modal-content" method="POST" action="{{ url('menu/update/' . $item->id) }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="edit{{ $item->id }}Label">Modal Edit {{ $title }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="menu" class="form-label">Menu</label>
                        <input type="text" name="menu" id="menu" class="form-control" placeholder="Menu" value="{{ $item->menu }}" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
    @endforeach

    @foreach($data as $item)
    <!-- Modal -->
    <div class="modal fade" id="hapus{{ $item->id }}" tabindex="-1" aria-labelledby="hapus{{ $item->id }}Label" aria-hidden="true">
        <div class="modal-dialog">
        <form method="POST" action="{{ url('menu/destroy/' . $item->id) }}" class="modal-content">
            @csrf
            <div class="modal-header">
            <h5 class="modal-title" id="hapus{{ $item->id }}Label">Modal Hapus</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <div class="modal-body">
                Apakah anda ingin menghapus data ini <h3>{{ $item->menu }}</h3>
            <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-danger">Hapus</button>
            </div>
        </div>
        </div>
    </div>
    @endforeach

@endsection