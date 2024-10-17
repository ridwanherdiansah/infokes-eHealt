@extends('Layout.Admin.main')

@section('main')

    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">{{ $title }}</h1>
        <p class="my-4">Lorem ipsum dolor sit amet consectetur, adipisicing elit.</p>
        <div class="my-4">
            <button data-toggle="modal" data-target="#tambahPemabayaran" class="btn btn-primary btn-icon-split">
                <span class="icon text-white-50">
                    <i class="fas fa-plus"></i>
                </span>
                <span class="text">Tambah</span>
            </button>
            
            <button data-toggle="modal" data-target="#filterPembayaran" class="btn btn-secondary btn-icon-split">
                <span class="icon text-white-50">
                    <i class="fas fa-filter"></i>
                </span>
                <span class="text">Filter</span>
            </button>
            
            <button data-toggle="modal" data-target="#exportPembayaran" class="btn btn-success btn-icon-split">
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
                    
                    <form action="{{ route('pembayaran.search') }}" method="GET">
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
                                <th>Nomor Pembayaran</th>
                                <th>Nomor RM</th>
                                <th>Tanggal Pembayarn</th>
                                <th>Biaya Konsultasi</th>
                                <th>Biaya Pemeriksaan</th>
                                <th>Biaya Obat</th>
                                <th>Total Pembayaran</th>
                                <th>Tanggal</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data as $item)
                            <tr>
                                <th scope="row">{{ $loop->iteration }}</th>
                                <td>{{ $item->nomor_pembayaran }}</td>
                                <td>{{ $item->nomor_rekam_medis }}</td>
                                <td>{{ $item->tanggal_pembayaran }}</td>
                                <td>@currency( $item->biaya_konsultasi )</td>
                                <td>@currency( $item->biaya_pemeriksaan )</td>
                                <td>@currency( $item->biaya_obat )</td>
                                <td>@currency( $item->total_pembayaran )</td>
                                <td>{{ $item->created_at }}</td>
                                <td>
                                    @if(auth()->user()->status == 1)
                                    <button 
                                        href="javascript:;" 
                                        data-toggle="modal" 
                                        data-target="#editPembayaran{{ $item->id }}"  
                                        class="btn btn-warning btn-circle btn-sm">
                                        <i class="fas fa-exclamation-triangle"></i>
                                    </button>
                                    <button 
                                        href="javascript:;" 
                                        data-toggle="modal" 
                                        data-target="#hapusPembayaran{{ $item->id }}"  
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
    <div class="modal fade" id="tambahPemabayaran" tabindex="-1" aria-labelledby="tambahPemabayaranLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form class="modal-content" method="POST" action="{{ url('pembayaran') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="tambahPemabayaranLabel">Modal Tambah {{ $title }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="nomor_rekam_medis">Nomor Rekam Medis</label>
                        <input type="text" class="form-control" id="nomor_rekam_medis" name="nomor_rekam_medis">
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="biaya_konsultasi">Biaya Konsultasi</label>
                            <input type="text" class="form-control" id="biaya_konsultasi" name="biaya_konsultasi" oninput="calculateTotal()">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="biaya_pemeriksaan">Biaya Pemeriksaan</label>
                            <input type="text" class="form-control" id="biaya_pemeriksaan" name="biaya_pemeriksaan" oninput="calculateTotal()">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="biaya_obat">Biaya Obat</label>
                            <input type="text" class="form-control" id="biaya_obat" name="biaya_obat" oninput="calculateTotal()">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="total_pembayaran">Total Pembayaran</label>
                        <input type="text" class="form-control" id="total_pembayaran" name="total_pembayaran" readonly>
                    </div>
                    <div class="form-group text-center">
                        <h1 id="total_pembayaran_display">{{ 'Rp ' . number_format($item->total_pembayaran, 2, ',', '.') }}</h1>
                    </div>
                </div>  
                <script>
                    function calculateTotal() {
                        var biayaKonsultasi = parseFloat(document.getElementById('biaya_konsultasi').value) || 0;
                        var biayaPemeriksaan = parseFloat(document.getElementById('biaya_pemeriksaan').value) || 0;
                        var biayaObat = parseFloat(document.getElementById('biaya_obat').value) || 0;
                
                        var totalPembayaran = biayaKonsultasi + biayaPemeriksaan + biayaObat;
                
                        var formattedTotal = totalPembayaran.toLocaleString('id-ID');
                
                        document.getElementById('total_pembayaran').value = formattedTotal;
                        document.getElementById('total_pembayaran_display').innerText = formattedTotal;
                    }
                
                    // Initialize the total payment display on page load
                    document.addEventListener('DOMContentLoaded', function() {
                        calculateTotal();
                    });
                </script>            
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Filter -->
    <div class="modal fade" id="filterPembayaran" tabindex="-1" aria-labelledby="filterPembayaranLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form class="modal-content" method="GET" action="{{ url('pembayaran/filter') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="filterPembayaranLabel">Modal Filter {{ $title }}</h5>
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
    <div class="modal fade" id="exportPembayaran" tabindex="-1" aria-labelledby="exportPembayaranLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form class="modal-content" method="GET" action="{{ url('pembayaran/export') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="exportPembayaranLabel">Modal Export {{ $title }}</h5>
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
<div class="modal fade" id="editPembayaran{{ $item->id }}" tabindex="-1" aria-labelledby="editPembayaran{{ $item->id }}Label" aria-hidden="true">
    <div class="modal-dialog">
        <form class="modal-content" method="POST" action="{{ url('pembayaran/update/' . $item->id) }}">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="editPembayaran{{ $item->id }}Label">Modal Edit {{ $title }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="nomor_rekam_medis">Nomor Rekam Medis</label>
                    <input type="text" class="form-control" id="nomor_rekam_medis" name="nomor_rekam_medis" value="{{ $item->nomor_rekam_medis }}" readonly>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="biaya_konsultasi">Biaya Konsultasi</label>
                        <input type="text" class="form-control" id="biaya_konsultasi" name="biaya_konsultasi" value="{{ $item->biaya_konsultasi }}" oninput="calculateTotal()">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="biaya_pemeriksaan">Biaya Pemeriksaan</label>
                        <input type="text" class="form-control" id="biaya_pemeriksaan" name="biaya_pemeriksaan" value="{{ $item->biaya_pemeriksaan }}" oninput="calculateTotal()">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="biaya_obat">Biaya Obat</label>
                        <input type="text" class="form-control" id="biaya_obat" name="biaya_obat" value="{{ $item->biaya_obat }}" oninput="calculateTotal()">
                    </div>
                </div>
                <div class="form-group">
                    <label for="total_pembayaran">Total Pembayaran</label>
                    <input type="text" class="form-control" id="total_pembayaran" name="total_pembayaran" value="{{ $item->total_pembayaran }}" readonly>
                </div>
                <div class="form-group text-center">
                    <h1 id="total_pembayaran_display">{{ 'Rp ' . number_format($item->total_pembayaran, 2, ',', '.') }}</h1>
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

@push('scripts')
<script>
    function calculateTotal() {
        var biaya_konsultasi = parseFloat(document.getElementById('biaya_konsultasi').value) || 0;
        var biaya_pemeriksaan = parseFloat(document.getElementById('biaya_pemeriksaan').value) || 0;
        var biaya_obat = parseFloat(document.getElementById('biaya_obat').value) || 0;
        
        var total_pembayaran = biaya_konsultasi + biaya_pemeriksaan + biaya_obat;
        
        document.getElementById('total_pembayaran').value = total_pembayaran;
        document.getElementById('total_pembayaran_display').textContent = 'Rp ' + total_pembayaran.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
    }
</script>
@endpush


    {{-- Modal hapus --}}
    @foreach($data as $item)
    <div class="modal fade" id="hapusPembayaran{{ $item->id }}" tabindex="-1" aria-labelledby="hapusPembayaran{{ $item->id }}Label" aria-hidden="true">
        <div class="modal-dialog">
        <form method="POST" action="{{ url('pembayaran/destroy/' . $item->id) }}" class="modal-content">
            @csrf
            <div class="modal-header">
            <h5 class="modal-title" id="hapusPembayaran{{ $item->id }}Label">Modal title</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <div class="modal-body">
                Apakah anda ingin menghapus data ini <h3>{{ $item->nomor_pembayaran }}</h3>
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-danger">Delete</button>
            </div>
        </form>
        </div>
    </div>
    @endforeach

@endsection