<?php

namespace App\Http\Controllers;

use Maatwebsite\Excel\Facades\Excel;
use App\Exports\KunjunganExport;
use App\Models\Kunjungan;
use App\Models\Pasien;
use App\Models\Poli;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;


class KunjunganController extends Controller
{
    public function updatePembayaran(Request $request, $id)
    {
        try {

            // update berdasarkan id
            $update = Kunjungan::where("id", $id)->update([
                'pembayaran' => 2,
            ]);

            // Kondisi Data Berhasil atau Gagal
            if($update){
                return back()->with('success','Berhasil edit Pembayaran');
            }else{
                return back()->with('error','Gagal edit Pembayaran');
            }

        } catch (\Exception $e) {
            // Tangani kesalahan dengan mengembalikan pesan error
            return back()->with('error', $e->getMessage());
        }
    }

    public function pembayaran(Request $request, $id)
    {
        $nomor_rekam_medis = $request->nomor_rekam_medis;
        $nomor_pembayaran = strtoupper(substr(md5(Str::random(10)), 0, 5));
        $tanggal_pembayaran = Carbon::now()->toDateString();

        // Membuat form dari Request Form
        $insert = Pembayaran::create([
            'nomor_pembayaran' => $nomor_pembayaran,
            'nomor_rekam_medis' => $request->nomor_rekam_medis,
            'tanggal_pembayaran' => $tanggal_pembayaran,
            'biaya_konsultasi' => $request->biaya_konsultasi,
            'biaya_pemeriksaan' => $request->biaya_pemeriksaan,
            'biaya_obat' => $request->biaya_obat,
            'total_pembayaran' => $request->total_pembayaran,
        ]);

        // Kondisi Data Berhasil atau Gagal
        if($insert){
            return back()->with('success','Berhasil Tambah Pembayaran');
        }else{
            return back()->with('error','Gagal Tambah Pembayaran');
        }

        return back()->with('error','Nomor Rekam Medis Belum Terdaftar');
    }

    public function destroy(Request $request, $id)
    {
        try {
            $delete = Kunjungan::where('id',$id)->delete();

            if($delete){
                return back()->with('success','Berhasil hapus Kunjungan');
            }else{
                return back()->with('error','Gagal hapus Kunjungan');
            }

        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
        
    }

    public function update(Request $request, $id)
    {
        try {
            // Validasi request
            $request->validate([
                'no_ktp' => 'required',
                'dokter_penanggung_jawab' => 'required|max:255',
                'poli_departemen' => 'required',
                'keperluan_kunjungan' => 'required|max:255',
            ], [
                'no_ktp.required' => 'No KTP harus diisi',
                'dokter_penanggung_jawab.required' => 'Dokter penanggung jawab harus diisi',
                'dokter_penanggung_jawab.max' => 'Dokter penanggung jawab maksimal 255 karakter',
                'poli_departemen.required' => 'Poli departemen harus diisi',
                'keperluan_kunjungan.required' => 'Keperluan kunjungan harus diisi',
                'keperluan_kunjungan.max' => 'Keperluan kunjungan maksimal 255 karakter',
            ]);

            $no_ktp = $request->no_ktp;
            $pasien = Pasien::where('no_ktp', $no_ktp)->first();

            if ($pasien) {
                
                // update berdasarkan id
                $update = Kunjungan::where("id", $id)->update([
                    'no_ktp' => $request->no_ktp,
                    'dokter_penanggung_jawab' => $request->dokter_penanggung_jawab,
                    'poli_departemen' => $request->poli_departemen,
                    'keperluan_kunjungan' => $request->keperluan_kunjungan,
                    'catatan_tambahan' => $request->catatan_tambahan,
                ]);

                // Kondisi Data Berhasil atau Gagal
                if($update){
                    return back()->with('success','Berhasil edit Menu');
                }else{
                    return back()->with('error','Gagal edit Menu');
                }
                
            }
            
            return back()->with('error','No KTP yang di masukan belum terdaftar!');

        } catch (\Exception $e) {
            // Tangani kesalahan dengan mengembalikan pesan error
            return back()->with('error', $e->getMessage());
        }
    }

    public function search(Request $request)
    {
        try {
            $request->validate([
                'search' => 'required|max:255',
            ],[
                'search.required' => 'search harus diisi',
                'search.max' => 'search maksimal 255 karakter',
            ]);

            $search = $request->search;
        
            $type_menu = 'kunjungan';
            $title = 'kunjungan';

            $poli = Poli::all();
            $data = Kunjungan::select(
                'kunjungans.*', 
                'pasiens.nama', 
                'pasiens.no_ktp', 
                'polis.poli',
                DB::raw("CASE 
                            WHEN EXISTS (
                                SELECT 1 
                                FROM pembayarans 
                                WHERE pembayarans.nomor_pembayaran = kunjungans.pembayaran
                            ) 
                            THEN 0 
                            ELSE kunjungans.pembayaran 
                         END AS pembayaran")
            )
                    ->leftJoin('polis', 'kunjungans.poli_departemen', '=', 'polis.id')
                    ->leftJoin('pasiens', 'kunjungans.no_ktp', '=', 'pasiens.no_ktp')
                    ->where('kunjungans.nomor_rekam_medis', 'like', '%' . $search . '%')
                    ->orWhere('kunjungans.no_ktp', 'like', '%' . $search . '%')
                    ->orWhere('pasiens.nama', 'like', '%' . $search . '%')
                    ->orderBy('kunjungans.id', 'desc')
                    ->paginate(10);
            
            return view('Pages.Admin.Kunjungan.index', compact('data', 'poli', 'type_menu', 'title'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function export(Request $request)
    {
        try {
            $request->validate([
                'tanggal_awal' => 'required',
                'tanggal_akhir' => 'required',
            ],[
                'tanggal_awal.required' => 'tanggal awal harus diisi',
                'tanggal_akhir.required' => 'tanggal akhir harus diisi',
            ]);
            
            $data = Kunjungan::select(
                        'kunjungans.nomor_rekam_medis', 
                        'pasiens.nama', 
                        'kunjungans.no_ktp',
                        'kunjungans.tanggal_kunjungan',
                        'kunjungans.dokter_penanggung_jawab',
                        'polis.poli',
                        'kunjungans.keperluan_kunjungan',
                        DB::raw("CASE 
                            WHEN kunjungans.pembayaran = 1 THEN 'Belum Bayar' 
                            WHEN kunjungans.pembayaran = 2 THEN 'Pendaftaran di Batalkan' 
                            ELSE 'Pembayaran Sukses' 
                        END AS pembayaran"),
                        'kunjungans.catatan_pembayaran',
                        'kunjungans.created_at',
                    )
                    ->leftJoin('polis', 'kunjungans.poli_departemen', '=', 'polis.id')
                    ->leftJoin('pasiens', 'kunjungans.no_ktp', '=', 'pasiens.no_ktp')
                    ->whereDate('kunjungans.created_at', '>=', $request->tanggal_awal)
                    ->whereDate('kunjungans.created_at', '<=', $request->tanggal_akhir)
                    ->orderBy('id', 'desc')
                    ->get();
            return Excel::download(new KunjunganExport($data), 'Kunjungan.xlsx');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function filter(Request $request)
    {
        try {
            $type_menu = 'kunjungan';
            $title = 'kunjungan';
            
            $request->validate([
                'tanggal_awal' => 'required',
                'tanggal_akhir' => 'required',
            ],[
                'tanggal_awal.required' => 'tanggal awal harus diisi',
                'tanggal_akhir.required' => 'tanggal akhir harus diisi',
            ]);

            
            $poli = Poli::all();
            $data = Kunjungan::select(
                'kunjungans.*', 
                'pasiens.nama', 
                'pasiens.no_ktp', 
                'polis.poli',
                DB::raw("CASE 
                            WHEN EXISTS (
                                SELECT 1 
                                FROM pembayarans 
                                WHERE pembayarans.nomor_pembayaran = kunjungans.pembayaran
                            ) 
                            THEN 0 
                            ELSE kunjungans.pembayaran 
                         END AS pembayaran")
            )
                    ->leftJoin('polis', 'kunjungans.poli_departemen', '=', 'polis.id')
                    ->leftJoin('pasiens', 'kunjungans.no_ktp', '=', 'pasiens.no_ktp')
                    ->whereDate('created_at', '>=', $request->tanggal_awal)
                    ->whereDate('created_at', '<=', $request->tanggal_akhir)
                    ->orderBy('id', 'desc')
                    ->paginate(10);
            
            return view('Pages.Admin.Kunjungan.index', compact('data', 'poli', 'type_menu', 'title'));

        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'no_ktp' => 'required',
            'nama' => 'required|max:255',
            'tempat_lahir' => 'required|max:255',
            'tanggal_lahir' => 'required',
            'jenis_kelamin' => 'required',
            'alamat' => 'required|max:255',
            'rt' => 'required|max:10',
            'rw' => 'required|max:10',
            'desa' => 'required|max:255',
            'kecamatan' => 'required|max:255',
            'pekerjaan' => 'required|max:255',
            'dokter_penanggung_jawab' => 'required|max:255',
            'poli_departemen' => 'required',
            'keperluan_kunjungan' => 'required|max:255',
        ], [
            'no_ktp.required' => 'No KTP harus diisi',
            'nama.required' => 'Nama harus diisi',
            'nama.max' => 'Nama maksimal 255 karakter',
            'tempat_lahir.required' => 'Tempat lahir harus diisi',
            'tempat_lahir.max' => 'Tempat lahir maksimal 255 karakter',
            'tanggal_lahir.required' => 'Tanggal lahir harus diisi',
            'jenis_kelamin.required' => 'Jenis kelamin harus diisi',
            'alamat.required' => 'Alamat harus diisi',
            'alamat.max' => 'Alamat maksimal 255 karakter',
            'rt.required' => 'RT harus diisi',
            'rt.max' => 'RT maksimal 10 karakter',
            'rw.required' => 'RW harus diisi',
            'rw.max' => 'RW maksimal 10 karakter',
            'desa.required' => 'Desa harus diisi',
            'desa.max' => 'Desa maksimal 255 karakter',
            'kecamatan.required' => 'Kecamatan harus diisi',
            'kecamatan.max' => 'Kecamatan maksimal 255 karakter',
            'pekerjaan.required' => 'Pekerjaan harus diisi',
            'pekerjaan.max' => 'Pekerjaan maksimal 255 karakter',
            'dokter_penanggung_jawab.required' => 'Dokter penanggung jawab harus diisi',
            'dokter_penanggung_jawab.max' => 'Dokter penanggung jawab maksimal 255 karakter',
            'poli_departemen.required' => 'Poli departemen harus diisi',
            'keperluan_kunjungan.required' => 'Keperluan kunjungan harus diisi',
            'keperluan_kunjungan.max' => 'Keperluan kunjungan maksimal 255 karakter',
        ]);

        $no_ktp = $request->no_ktp;
        $pasien = Pasien::where('no_ktp', $no_ktp)->first();
        $nomorRekamMedis = strtoupper(substr(md5(Str::random(10)), 0, 5));
        $tanggalKunjungan = Carbon::now()->toDateString();

        if (!$pasien) {
            $insertPasien = Pasien::create([
                'no_ktp' => $request->no_ktp,
                'nama' => $request->nama,
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'jenis_kelamin' => $request->jenis_kelamin,
                'alamat' => $request->alamat,
                'rt' => $request->rt,
                'rw' => $request->rw,
                'desa' => $request->desa,
                'kecamatan' => $request->kecamatan,
                'pekerjaan' => $request->pekerjaan,
            ]);
        }

        $insert = Kunjungan::create([
            'nomor_rekam_medis' => $nomorRekamMedis,
            'no_ktp' => $request->no_ktp,
            'tanggal_kunjungan' => $tanggalKunjungan,
            'dokter_penanggung_jawab' => $request->dokter_penanggung_jawab,
            'poli_departemen' => $request->poli_departemen,
            'keperluan_kunjungan' => $request->keperluan_kunjungan,
            'pembayaran' => 1,
            'catatan_tambahan' => $request->catatan_tambahan,
        ]);

        // Kondisi Data Berhasil atau Gagal
        if ($insert) {
            return redirect()->route('kunjungan.index')->with('success', 'Berhasil Tambah Pasien');
        } else {
            return redirect()->route('kunjungan.index')->with('error', 'Gagal Tambah Pasien');
        }
    }

    public function create(Request $request)
    {
        try {
            $no_ktp = $request->no_ktp;
            $type_menu = "buat kunjungan";
            $title = "buat kunjungan";
            $poli = Poli::all();
            $pasien = Pasien::where('no_ktp', $no_ktp)->first();
            return view('Pages.Admin.Kunjungan.create', compact('pasien', 'poli', 'type_menu', 'title'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function index()
    {
        try {
            $type_menu = 'kunjungan';
            $title = 'kunjungan';
            $poli = Poli::all();
            $data = Kunjungan::select(
                'kunjungans.*', 
                'pasiens.nama', 
                'pasiens.no_ktp', 
                'polis.poli',
                DB::raw("CASE 
                            WHEN EXISTS (
                                SELECT 1 
                                FROM pembayarans 
                                WHERE pembayarans.nomor_pembayaran = kunjungans.pembayaran
                            ) 
                            THEN 0 
                            ELSE kunjungans.pembayaran 
                         END AS pembayaran")
            )
            ->leftJoin('polis', 'kunjungans.poli_departemen', '=', 'polis.id')
            ->leftJoin('pasiens', 'kunjungans.no_ktp', '=', 'pasiens.no_ktp')
            ->orderBy('id', 'desc')
            ->paginate(10);
            return view('Pages.Admin.Kunjungan.index', compact('data', 'poli', 'type_menu', 'title'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
