@extends('Layout.Admin.main')

@section('main')

    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">{{ $title }}</h1>
        <p class="my-4">Lorem ipsum dolor sit amet consectetur, adipisicing elit.</p>

        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <div class="d-flex justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Tabel {{ $title }}</h6>
                </div>
                
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Menu</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($menu as $item)
                            <tr>
                                <th scope="row">{{ $loop->iteration }}</th>
                                <td>{{ $item->menu }}</td>
                                <td>
                                    <label class="custom-switch mt-2" onclick="window.location='{{ route('users.accessMenuUpdate', ['role_id' => $users->id, 'menu_id' => $item->id]) }}';">
                                        <input type="checkbox" name="custom-switch-checkbox" class="custom-switch-input"
                                        {{ \App\Helpers\AccessHelper::check_access($users->id, $item->id) }}
                                        >
                                        <span class="custom-switch-indicator"></span>
                                    </label>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->

@endsection