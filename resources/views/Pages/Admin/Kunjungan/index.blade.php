@extends('Layout.Admin.main')

@section('main')

    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">{{ $title }}</h1>
        <p class="my-4">Lorem ipsum dolor sit amet consectetur, adipisicing elit.</p>
        <div class="my-4">
            <button data-toggle="modal" data-target="#tambahKunjungan" class="btn btn-primary btn-icon-split">
                <span class="icon text-white-50">
                    <i class="fas fa-plus"></i>
                </span>
                <span class="text">Tambah</span>
            </button>
            
            <button data-toggle="modal" data-target="#filterKunjungan" class="btn btn-secondary btn-icon-split">
                <span class="icon text-white-50">
                    <i class="fas fa-filter"></i>
                </span>
                <span class="text">Filter</span>
            </button>
            
            <button data-toggle="modal" data-target="#exportKunjungan" class="btn btn-success btn-icon-split">
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
                    
                    <form action="{{ route('kunjungan.search') }}" method="GET">
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
                                <th>No RM</th>
                                <th>Nama</th>
                                <th>No KTP</th>
                                <th>Tanggal Kunjungan</th>
                                <th>Dokter</th>
                                <th>Poli</th>
                                <th>Keperluan Kunjungan</th>
                                <th>Pembayaran</th>
                                <th>Catatan Tambahan</th>
                                <th>Tanggal Dibuat</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data as $item)
                            <tr>
                                <th scope="row">{{ $loop->iteration }}</th>
                                <td>{{ $item->nomor_rekam_medis }}</td>
                                <td>{{ $item->nama }}</td>
                                <td>{{ $item->no_ktp }}</td>
                                <td>{{ $item->tanggal_kunjungan }}</td>
                                <td>{{ $item->dokter_penanggung_jawab }}</td>
                                <td>{{ $item->poli }}</td>
                                <td>{{ $item->keperluan_kunjungan }}</td>
                                <td>
                                    @if($item->pembayaran == 1)
                                        <button 
                                            href="javascript:;" 
                                            data-toggle="modal" 
                                            data-target="#editPembayaran{{ $item->id }}"
                                            class="btn btn-warning btn-icon-split">
                                            <span class="icon text-white-50">
                                                <span class="text">Bayar</span>
                                            </span>
                                        </button>
                                    @elseif($item->pembayaran == 2)
                                        <button class="btn btn-danger btn-icon-split">
                                            <span class="icon text-white-50">
                                                <span class="text">Pendaftaran Di Batalkan</span>
                                            </span>
                                        </button>
                                    @else
                                        <button class="btn btn-success btn-icon-split">
                                            <span class="icon text-white-50">
                                                <span class="text">Pembayaran Success</span>
                                            </span>
                                        </button>
                                    @endif
                                <td>{{ $item->catatan_tambahan }}</td>
                                <td>{{ $item->created_at }}</td>
                                <td>
                                    @if($item->pembayaran == 1)
                                    <button 
                                        href="javascript:;" 
                                        data-toggle="modal" 
                                        data-target="#batalPemabayarn{{ $item->id }}"
                                        class="btn btn-danger btn-icon-split">
                                        <span class="icon text-white-50">
                                            <span class="text">Batalkan Kunjungan</span>
                                        </span>
                                    </button>
                                    @endif

                                    @if(auth()->user()->status == 1)
                                    <button 
                                        href="javascript:;" 
                                        data-toggle="modal" 
                                        data-target="#editKunjungan{{ $item->id }}"  
                                        class="btn btn-warning btn-circle btn-sm">
                                        <i class="fas fa-exclamation-triangle"></i>
                                    </button>
                                    <button 
                                        href="javascript:;" 
                                        data-toggle="modal" 
                                        data-target="#hapusKunjungan{{ $item->id }}"  
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

    <!-- Modal Batal -->
    @foreach($data as $item)
    <div class="modal fade" id="batalPemabayarn{{ $item->id }}" tabindex="-1" aria-labelledby="batalPemabayarn{{ $item->id }}Label" aria-hidden="true">
        <div class="modal-dialog">
        <form method="GET" action="{{ url('kunjungan/updatePembayaran/' . $item->id) }}" class="modal-content">
            @csrf
            <div class="modal-header">
            <h5 class="modal-title" id="batalPemabayarn{{ $item->id }}Label">Modal title</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <div class="modal-body">
                Apakah anda ingin membatalkan kunjungan ini <h3>{{ $item->nomor_rekam_medis }}</h3>
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-danger">Batal changes</button>
            </div>
        </form>
        </div>
    </div>
    @endforeach

    <!-- Modal Bayar -->
    @foreach($data as $item)
    <div class="modal fade" id="editPembayaran{{ $item->id }}" tabindex="-1" aria-labelledby="editPembayaran{{ $item->id }}Label" aria-hidden="true">
        <div class="modal-dialog">
            <form class="modal-content" method="POST" action="{{ url('kunjungan/pembayaran/' . $item->id) }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="editPembayaran{{ $item->id }}Label">Modal Pembayaran {{ $title }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="nomor_rekam_medis">Nomor Rekam Medis</label>
                        <input type="text" class="form-control" id="nomor_rekam_medis" name="nomor_rekam_medis" value="{{ $item->nomor_rekam_medis }}" readonly>
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
                        <h1 id="total_pembayaran_display">Rp.{{ 'Rp ' . number_format($item->total_pembayaran, 2, ',', '.') }}</h1>
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
    @endforeach

    <!-- Modal Tambah -->
    <div class="modal fade" id="tambahKunjungan" tabindex="-1" aria-labelledby="tambahKunjunganLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form class="modal-content" method="GET" action="{{ url('kunjungan/create') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="tambahKunjunganLabel">Modal Create {{ $title }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="no_ktp">No KTP</label>
                        <input type="text" name="no_ktp" class="form-control" id="no_ktp">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Cek</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Filter -->
    <div class="modal fade" id="filterKunjungan" tabindex="-1" aria-labelledby="filterKunjunganLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form class="modal-content" method="GET" action="{{ url('kunjungan/filter') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="filterKunjunganLabel">Modal Filter {{ $title }}</h5>
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
    <div class="modal fade" id="exportKunjungan" tabindex="-1" aria-labelledby="exportKunjunganLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form class="modal-content" method="GET" action="{{ url('kunjungan/export') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="exportKunjunganLabel">Modal Export {{ $title }}</h5>
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
    <div class="modal fade" id="editKunjungan{{ $item->id }}" tabindex="-1" aria-labelledby="editKunjungan{{ $item->id }}}Label" aria-hidden="true">
        <div class="modal-dialog">
            <form class="modal-content" method="POST" action="{{ url('kunjungan/update/' . $item->id) }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="editKunjungan{{ $item->id }}Label">Modal Edit {{ $title }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="no_ktp">No KTP</label>
                        <input type="text" class="form-control" id="no_ktp" name="no_ktp" value="{{ $item->no_ktp }}">
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="dokter_penanggung_jawab">Dokter Penanggung Jawab</label>
                            <input type="text" class="form-control" id="dokter_penanggung_jawab" name="dokter_penanggung_jawab" value="{{ $item->dokter_penanggung_jawab }}" >
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
                        <input type="text" class="form-control" id="keperluan_kunjungan" name="keperluan_kunjungan" value="{{ $item->keperluan_kunjungan }}">
                    </div>
                    <div class="form-group">
                        <label for="catatan_tambahan">Catatan Tambahan</label>
                        <textarea class="form-control" id="catatan_tambahan" name="catatan_tambahan" rows="3">{{ $item->catatan_tambahan }}</textarea>
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

    <!-- Modal Hapus -->
    @foreach($data as $item)
    <div class="modal fade" id="hapusKunjungan{{ $item->id }}" tabindex="-1" aria-labelledby="hapusKunjungan{{ $item->id }}Label" aria-hidden="true">
        <div class="modal-dialog">
        <form method="POST" action="{{ url('kunjungan/destroy/' . $item->id) }}" class="modal-content">
            @csrf
            <div class="modal-header">
            <h5 class="modal-title" id="hapusKunjungan{{ $item->id }}Label">Modal title</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <div class="modal-body">
                Apakah anda ingin menghapus data ini <h3>{{ $item->nomor_rekam_medis }}</h3>
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