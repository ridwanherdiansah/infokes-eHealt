<?php

namespace App\Http\Controllers;

use App\Models\Poli;
use App\Exports\PoliExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Requests\StorePoliRequest;
use App\Http\Requests\UpdatePoliRequest;
use Illuminate\Http\Request;

class PoliController extends Controller
{
    public function destroy(Request $request, $id)
    {
        try {
            $delete = Poli::where('id',$id)->delete();

            if($delete){
                return back()->with('success','Berhasil hapus Poli');
            }else{
                return back()->with('error','Gagal hapus Poli');
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
                'poli' => 'required|max:255',
            ], [
                'poli.required' => 'poli harus diisi',
                'poli.max' => 'poli maksimal 255 karakter',
            ]);

            // update berdasarkan id
            $update = Poli::where("id", $id)->update([
                'poli' => $request->poli,
            ]);

            // Kondisi Data Berhasil atau Gagal
            if($update){
                return back()->with('success','Berhasil edit Menu');
            }else{
                return back()->with('error','Gagal edit Menu');
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
        
            $type_menu = 'poli';
            $title = 'poli';
            $data = Poli::orderBy('id', 'desc')->where('poli', 'like', '%' . $search . '%')->paginate(10);
            return view('Pages.Admin.Poli.index', compact('data', 'type_menu', 'title'));
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

            $data = Poli::select('*')
            ->whereDate('created_at', '>=', $request->tanggal_awal)
            ->whereDate('created_at', '<=', $request->tanggal_akhir)
            ->orderBy('id', 'desc')
            ->get();

            return Excel::download(new PoliExport($data), 'Poli.xlsx');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function filter(Request $request)
    {
        try {
            $type_menu = 'poli';
            $title = 'poli';
            
            $request->validate([
                'tanggal_awal' => 'required',
                'tanggal_akhir' => 'required',
            ],[
                'tanggal_awal.required' => 'tanggal awal harus diisi',
                'tanggal_akhir.required' => 'tanggal akhir harus diisi',
            ]);

            $data = Poli::select('*')
            ->whereDate('created_at', '>=', $request->tanggal_awal)
            ->whereDate('created_at', '<=', $request->tanggal_akhir)
            ->orderBy('id', 'desc')
            ->paginate(10);
            
            return view('Pages.Admin.Poli.index', compact('data', 'type_menu', 'title'));

        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'poli' => 'required|max:255',
            ],[
                'poli.required' => 'poli harus diisi',
                'poli.max' => 'poli maksimal 255 karakter',
            ]);

            // Membuat form dari Request Form
            $insert = Poli::create([
                'poli' => $request->poli,
            ]);

            // Kondisi Data Berhasil atau Gagal
            if($insert){
                return back()->with('success','Berhasil Tambah Menu');
            }else{
                return back()->with('error','Gagal Tambah Menu');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function index()
    {
        try {
            $type_menu = 'poli';
            $title = 'poli';
            $data = Poli::orderBy('id', 'desc')->paginate(10);
            return view('Pages.Admin.Poli.index', compact('data', 'type_menu', 'title'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
