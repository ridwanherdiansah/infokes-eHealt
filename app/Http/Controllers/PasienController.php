<?php

namespace App\Http\Controllers;

use App\Models\Pasien;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PasienExport;
use App\Http\Requests\StorePasienRequest;
use App\Http\Requests\UpdatePasienRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PasienController extends Controller
{
    public function destroy(Request $request, $id)
    {
        try {
            $delete = PasienExport::where('id',$id)->delete();

            if($delete){
                return back()->with('success','Berhasil hapus Pasien');
            }else{
                return back()->with('error','Gagal hapus Pasien');
            }

        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
        
    }

    public function update(Request $request, $id)
    {
        try {
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
            ],[
                'no_ktp.required' => 'No KTP harus diisi',
                'nama.required' => 'nama harus diisi',
                'nama.max' => 'nama maksimal 255 karakter',
                'tempat_lahir.required' => 'Tempat lahir harus diisi',
                'tempat_lahir.max' => 'Tempat lahir maksimal 255 karakter',
                'tanggal_lahir.required' => 'Tanggal Lahir harus diisi',
                'jenis_kelamin.required' => 'Jenis Kelamin harus diisi',
                'alamat.required' => 'alamat harus diisi',
                'alamat.max' => 'alamat maksimal 255 karakter',
                'rt.required' => 'RT harus diisi',
                'rt.max' => 'RT maksimal 255 karakter',
                'rw.required' => 'RW harus diisi',
                'rw.max' => 'RW maksimal 255 karakter',
                'desa.required' => 'Desa harus diisi',
                'desa.max' => 'Desa maksimal 255 karakter',
                'kecamatan.required' => 'kecamatan harus diisi',
                'kecamatan.max' => 'kecamatan maksimal 255 karakter',
                'pekerjaan.required' => 'pekerjaan harus diisi',
                'pekerjaan.max' => 'pekerjaan maksimal 255 karakter',
            ]);

            // $no_ktpNew = $request->no_ktp;
            // $pasien = Pasien::where('no_ktp', $no_ktpNew)->first();
            // if (!$pasien) {

                // update berdasarkan id
                $update = Pasien::where("id", $id)->update([
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

                // Kondisi Data Berhasil atau Gagal
                if($update){
                    return back()->with('success','Berhasil edit pasien');
                }else{
                    return back()->with('error','Gagal edit pasien');
                }
            
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
        
            $type_menu = 'pasien';
            $title = 'pasien';

            $data = Pasien::select('*')
                ->Where('no_ktp', 'like', '%' . $search . '%')
                ->orWhere('nama', 'like', '%' . $search . '%')
                ->orderBy('id', 'desc')
                ->paginate(10);

            return view('Pages.Admin.Pasien.index', compact('data', 'type_menu', 'title'));
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

            $data = Pasien::select('*',
                    DB::raw("CASE WHEN pasiens.jenis_kelamin = 1 THEN 'Laki Laki' ELSE 'Perempuan' END as jenis_kelamin")
                )
            ->whereDate('created_at', '>=', $request->tanggal_awal)
            ->whereDate('created_at', '<=', $request->tanggal_akhir)
            ->orderBy('id', 'desc')
            ->get();

            return Excel::download(new PasienExport($data), 'Pasien.xlsx');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function filter(Request $request)
    {
        try {
            $type_menu = 'pasien';
            $title = 'pasien';
            
            $request->validate([
                'tanggal_awal' => 'required',
                'tanggal_akhir' => 'required',
            ],[
                'tanggal_awal.required' => 'tanggal awal harus diisi',
                'tanggal_akhir.required' => 'tanggal akhir harus diisi',
            ]);

            $data = Pasien::select('*')
                ->whereDate('created_at', '>=', $request->tanggal_awal)
                ->whereDate('created_at', '<=', $request->tanggal_akhir)
                ->orderBy('id', 'desc')
                ->paginate(10);

            return view('Pages.Admin.Pasien.index', compact('data', 'type_menu', 'title'));

        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
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
            ],[
                'no_ktp.required' => 'No KTP harus diisi',
                'nama.required' => 'nama harus diisi',
                'nama.max' => 'nama maksimal 255 karakter',
                'tempat_lahir.required' => 'Tempat lahir harus diisi',
                'tempat_lahir.max' => 'Tempat lahir maksimal 255 karakter',
                'tanggal_lahir.required' => 'Tanggal Lahir harus diisi',
                'jenis_kelamin.required' => 'Jenis Kelamin harus diisi',
                'alamat.required' => 'alamat harus diisi',
                'alamat.max' => 'alamat maksimal 255 karakter',
                'rt.required' => 'RT harus diisi',
                'rt.max' => 'RT maksimal 255 karakter',
                'rw.required' => 'RW harus diisi',
                'rw.max' => 'RW maksimal 255 karakter',
                'desa.required' => 'Desa harus diisi',
                'desa.max' => 'Desa maksimal 255 karakter',
                'kecamatan.required' => 'kecamatan harus diisi',
                'kecamatan.max' => 'kecamatan maksimal 255 karakter',
                'pekerjaan.required' => 'pekerjaan harus diisi',
                'pekerjaan.max' => 'pekerjaan maksimal 255 karakter',
            ]);

            $no_ktpNew = $request->no_ktp;
            $pasien = Pasien::where('no_ktp', $no_ktpNew)->first();
            if (!$pasien) {
                // Membuat form dari Request Form
                $nomorRekamMedis = substr(md5(uniqid()), 0, 4);

                // Membuat form dari Request Form
                $insert = Pasien::create([
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

                // Kondisi Data Berhasil atau Gagal
                if($insert){
                    return back()->with('success','Berhasil Tambah Pasien');
                }else{
                    return back()->with('error','Gagal Tambah Pasien');
                }
            }
                return back()->with('error','No KTP sudah ada');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function index()
    {
        try {
            $type_menu = 'pasien';
            $title = 'pasien';
            $data = Pasien::select('*')
                ->orderBy('id', 'desc')
                ->paginate(10);

            return view('Pages.Admin.Pasien.index', compact('data', 'type_menu', 'title'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
