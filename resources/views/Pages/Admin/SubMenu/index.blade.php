@extends('Layout.Admin.main')

@section('main')

    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">{{ $title }}</h1>
        <p class="my-4">Lorem ipsum dolor sit amet consectetur, adipisicing elit.</p>
        <div class="my-4">
            <button data-toggle="modal" data-target="#tambahSubMenu" class="btn btn-primary btn-icon-split">
                <span class="icon text-white-50">
                    <i class="fas fa-plus"></i>
                </span>
                <span class="text">Tambah</span>
            </button>
            
            <button data-toggle="modal" data-target="#filterSubMenu" class="btn btn-secondary btn-icon-split">
                <span class="icon text-white-50">
                    <i class="fas fa-filter"></i>
                </span>
                <span class="text">Filter</span>
            </button>
            
            <button data-toggle="modal" data-target="#exportSubMenu" class="btn btn-success btn-icon-split">
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
                    
                    <form action="{{ route('subMenu.search') }}" method="GET">
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
                                <th>Id</th>
                                <th>Nama Menu</th>
                                <th>Nama Sub Menu</th>
                                <th>Url</th>
                                <th>Type Menu</th>
                                <th>Icon</th>
                                <th>Status</th>
                                <th>Tanggal Di Buat</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data as $item)
                            <tr>
                                <th scope="row">{{ $loop->iteration }}</th>
                                <td>{{ $item->id }}</td>
                                <td>{{ $item->menu_name }}</td>
                                <td>{{ $item->nama }}</td>
                                <td>{{ $item->url }}</td>
                                <td>{{ $item->type_menu }}</td>
                                <td>{{ $item->icon }}</td>
                                <td>
                                    <label class="custom-switch mt-2" onclick="window.location='{{ route('subMenu.status', ['id' => $item->id]) }}';">
                                        <input type="checkbox"
                                            name="custom-switch-checkbox"
                                            class="custom-switch-input"
                                            @if($item->status == "1") checked @endif>
                                        <span class="custom-switch-indicator"></span>
                                    </label>
                                </td>
                                <td>{{ $item->created_at }}</td>
                                <td>
                                    @if(auth()->user()->status == 1)
                                    <button 
                                        href="javascript:;" 
                                        data-toggle="modal" 
                                        data-target="#editSubmenu{{ $item->id }}"  
                                        class="btn btn-warning btn-circle btn-sm">
                                        <i class="fas fa-exclamation-triangle"></i>
                                    </button>
                                    <button 
                                        href="javascript:;" 
                                        data-toggle="modal" 
                                        data-target="#hapusSubmenu{{ $item->id }}"  
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
    <div class="modal fade" id="tambahSubMenu" tabindex="-1" aria-labelledby="tambahSubMenuLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form class="modal-content" method="POST" action="{{ url('subMenu') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="tambahSubMenuLabel">Modal Tambah {{ $title }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="menu_id">Menu</label>
                            <select id="menu_id" name="menu_id" class="form-control">
                                @foreach($menu as $item)
                                    <option value="{{ $item->id }}" >{{ $item->menu }}</option>
                                @endforeach
                            </select>
                            </div>
                        <div class="form-group col-md-6">
                        <label for="nama">Nama</label>
                        <input type="text" name="nama" class="form-control" id="nama">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="url">Url</label>
                        <input type="text" name="url" class="form-control" id="url">
                    </div>
                    <div class="form-group">
                        <label for="type_menu">Type Menu</label>
                        <input type="text" name="type_menu" class="form-control" id="type_menu">
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="icon">Icon</label>
                            <input type="text" name="icon" class="form-control" id="icon">
                            </div>
                        <div class="form-group col-md-6">
                        <label for="status">Status</label>
                        <select id="status" name="status" class="form-control">
                            <option value="1">Aktip</option>
                            <option value="0">Tidak Aktip</option>
                        </select>
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
    <div class="modal fade" id="filterSubMenu" tabindex="-1" aria-labelledby="filterSubMenuLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form class="modal-content" method="GET" action="{{ url('subMenu/filter') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="filterSubMenuLabel">Modal Filter {{ $title }}</h5>
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
    <div class="modal fade" id="exportSubMenu" tabindex="-1" aria-labelledby="exportSubMenuLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form class="modal-content" method="GET" action="{{ url('subMenu/export') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="exportSubMenuLabel">Modal Export {{ $title }}</h5>
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
    <div class="modal fade" id="editSubmenu{{ $item->id }}" tabindex="-1" aria-labelledby="editSubmenu{{ $item->id }}}Label" aria-hidden="true">
        <div class="modal-dialog">
            <form class="modal-content" method="POST" action="{{ url('subMenu/update/' . $item->id) }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="editSubmenu{{ $item->id }}}Label">Modal Edit {{ $title }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="menu_id">Menu</label>
                            <select id="menu_id" name="menu_id" class="form-control">
                                @foreach($menu as $itemMenus)
                                    <option value="{{ $itemMenus->id }}" >{{ $itemMenus->menu }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                        <label for="nama">Nama</label>
                        <input type="text" value="{{ $item->nama }}" name="nama" class="form-control" id="nama">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="url">Url</label>
                        <input type="text" value="{{ $item->url }}" name="url" class="form-control" id="url">
                    </div>
                    <div class="form-group">
                        <label for="type_menu">Type Menu</label>
                        <input type="text" value="{{ $item->type_menu }}" name="type_menu" class="form-control" id="type_menu">
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="icon">Icon</label>
                            <input type="text" value="{{ $item->icon }}" name="icon" class="form-control" id="icon">
                            </div>
                        <div class="form-group col-md-6">
                        <label for="status">Status</label>
                        <select id="status" name="status" class="form-control">
                            <option value="1">Aktip</option>
                            <option value="0">Tidak Aktip</option>
                        </select>
                        </div>
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
    <div class="modal fade" id="hapusSubmenu{{ $item->id }}" tabindex="-1" aria-labelledby="hapusSubmenu{{ $item->id }}Label" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" action="{{ url('subMenu/destroy/' . $item->id) }}" class="modal-content">
                @csrf
                <div class="modal-header">
                <h5 class="modal-title" id="hapusSubmenu{{ $item->id }}Label">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body">
                    Apakah anda ingin menghapus data ini <h3>{{ $item->nama }}</h3>
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-danger">Delete changes</button>
                </div>
            </form>
        </div>
    </div>
    @endforeach


@endsection