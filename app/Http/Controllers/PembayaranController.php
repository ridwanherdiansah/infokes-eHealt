<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use App\Models\Kunjungan;
use App\Http\Requests\StorepembayaranRequest;
use App\Http\Requests\UpdatepembayaranRequest;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PembayaranExport;

class PembayaranController extends Controller
{
    public function destroy(Request $request, $id)
    {
        try {
            $delete = Pembayaran::where('id',$id)->delete();

            if($delete){
                return back()->with('success','Berhasil hapus pembayaran');
            }else{
                return back()->with('error','Gagal hapus pembayaran');
            }

        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
        
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nomor_rekam_medis' => 'required',
        ],[
            'nomor_rekam_medis.required' => 'No KTP harus diisi',
        ]);

        $nomor_rekam_medis = $request->nomor_rekam_medis;
        $kunjungan = Kunjungan::where('nomor_rekam_medis', $nomor_rekam_medis)->first();

        if ($kunjungan) {
            // Membuat form dari Request Form
            $update = Pembayaran::where("id", $id)->update([
                'biaya_konsultasi' => $request->biaya_konsultasi,
                'biaya_pemeriksaan' => $request->biaya_pemeriksaan,
                'biaya_obat' => $request->biaya_obat,
                'total_pembayaran' => $request->total_pembayaran,
            ]);

            // Kondisi Data Berhasil atau Gagal
            if($update){
                return back()->with('success','Berhasil Update Pembayaran');
            }else{
                return back()->with('error','Gagal Update Pembayaran');
            }
        }

        return back()->with('error','Nomor Rekam Medis Belum Terdaftar');
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
        
            $type_menu = 'Pembayaran';
            $title = 'Pembayaran';
            $data = Pembayaran::orderBy('id', 'desc')
                    ->where('nomor_pembayaran', 'like', '%' . $search . '%')
                    ->orWhere('nomor_rekam_medis', 'like', '%' . $search . '%')
                    ->paginate(10);
            return view('Pages.Admin.Pembayaran.index', compact('data', 'type_menu', 'title'));
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
            
            $data = Pembayaran::select(
                        'pembayarans.nomor_pembayaran',
                        'pembayarans.nomor_rekam_medis',
                        'pembayarans.tanggal_pembayaran',
                        'pembayarans.biaya_konsultasi',
                        'pembayarans.biaya_pemeriksaan',
                        'pembayarans.biaya_obat',
                        'pembayarans.total_pembayaran',
                        'pembayarans.catatan',
                    )
                    ->whereDate('created_at', '>=', $request->tanggal_awal)
                    ->whereDate('created_at', '<=', $request->tanggal_akhir)
                    ->orderBy('id', 'desc')
                    ->get();

            return Excel::download(new PembayaranExport($data), 'Pembayaran.xlsx');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function filter(Request $request)
    {
        try {
            $type_menu = 'pembayaran';
            $title = 'pembayaran';
            
            $request->validate([
                'tanggal_awal' => 'required',
                'tanggal_akhir' => 'required',
            ],[
                'tanggal_awal.required' => 'tanggal awal harus diisi',
                'tanggal_akhir.required' => 'tanggal akhir harus diisi',
            ]);

            $type_menu = 'pembayaran';
            $title = 'pembayaran';
            $data = Pembayaran::select('*')
                    ->whereDate('created_at', '>=', $request->tanggal_awal)
                    ->whereDate('created_at', '<=', $request->tanggal_akhir)
                    ->orderBy('id', 'desc')
                    ->paginate(10);
            return view('Pages.Admin.Pembayaran.index', compact('data', 'type_menu', 'title'));

        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
    
    public function store(Request $request)
    {
        try {
        
            $request->validate([
                'nomor_rekam_medis' => 'required',
            ],[
                'nomor_rekam_medis.required' => 'No KTP harus diisi',
            ]);

            $nomor_rekam_medis = $request->nomor_rekam_medis;
            $kunjungan = Kunjungan::where('nomor_rekam_medis', $nomor_rekam_medis)->first();
            $nomor_pembayaran = strtoupper(substr(md5(Str::random(10)), 0, 5));
            $tanggal_pembayaran = Carbon::now()->toDateString();

            if ($kunjungan) {
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
            }

            return back()->with('error','Nomor Rekam Medis Belum Terdaftar');
               
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function index()
    {
        try {
            $type_menu = 'pembayaran';
            $title = 'pembayaran';
            $data = Pembayaran::select('*')
                    ->orderBy('id', 'desc')
                    ->paginate(10);
            return view('Pages.Admin.Pembayaran.index', compact('data', 'type_menu', 'title'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
