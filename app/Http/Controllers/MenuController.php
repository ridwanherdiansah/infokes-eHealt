<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\MenuExport;
use App\Http\Requests\StoreMenuRequest;
use App\Http\Requests\UpdateMenuRequest;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function destroy(Request $request, $id)
    {
        try {
            $delete = Menu::where('id',$id)->delete();

            if($delete){
                return back()->with('success','Berhasil hapus Menu');
            }else{
                return back()->with('error','Gagal hapus Menu');
            }

        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
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
        
            $type_menu = 'menu';
            $title = 'menu';
            $data = Menu::orderBy('id', 'desc')->where('menu', 'like', '%' . $search . '%')->paginate(10);
            return view('Pages.Admin.Menu.index', compact('data', 'type_menu', 'title'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function index()
    {
        try {
            $type_menu = 'menu';
            $title = 'menu';
            $data = Menu::orderBy('id', 'desc')->paginate(10);
            return view('Pages.Admin.Menu.index', compact('data', 'type_menu', 'title'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'menu' => 'required|max:255',
            ],[
                'menu.required' => 'menu harus diisi',
                'menu.max' => 'menu maksimal 255 karakter',
            ]);

            // Membuat form dari Request Form
            $insert = Menu::create([
                'menu' => $request->menu,
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

    public function update(Request $request, $id)
    {
        try {
            // Validasi request
            $request->validate([
                'menu' => 'required|max:255',
            ], [
                'menu.required' => 'Menu harus diisi',
                'menu.max' => 'Menu maksimal 255 karakter',
            ]);

            // update berdasarkan id
            $update = Menu::where("id", $id)->update([
                'menu' => $request->menu,
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

    public function filter(Request $request)
    {
        try {
            $type_menu = 'menu';
            $title = 'menu';
            
            $request->validate([
                'tanggal_awal' => 'required',
                'tanggal_akhir' => 'required',
            ],[
                'tanggal_awal.required' => 'tanggal awal harus diisi',
                'tanggal_akhir.required' => 'tanggal akhir harus diisi',
            ]);

            $data = Menu::select('*')
            ->whereDate('created_at', '>=', $request->tanggal_awal)
            ->whereDate('created_at', '<=', $request->tanggal_akhir)
            ->orderBy('id', 'desc')
            ->paginate(10);
            
            return view('Pages.Admin.Menu.index', compact('data', 'type_menu', 'title'));

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

            $menu = Menu::select('*')
            ->whereDate('created_at', '>=', $request->tanggal_awal)
            ->whereDate('created_at', '<=', $request->tanggal_akhir)
            ->orderBy('id', 'desc')
            ->get();

            return Excel::download(new MenuExport($menu), 'Menu.xlsx');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
