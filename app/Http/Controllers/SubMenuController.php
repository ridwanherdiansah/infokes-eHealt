<?php

namespace App\Http\Controllers;

use App\Models\Sub_menu;
use App\Models\Menu;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SubMenuExport;
use App\Http\Requests\StoreSub_menuRequest;
use App\Http\Requests\UpdateSub_menuRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubMenuController extends Controller
{
    public function status(Request $request, $id)
    {
        try {

            $data = Sub_menu::where('id', $id)->first();

            if ($data->status == 1 ) {
                
                // update berdasarkan id
                $update = Sub_menu::where("id", $id)->update([
                    'status' => 0,
                ]);

                // Kondisi Data Berhasil atau Gagal
                if($update){
                    return back()->with('success','Berhasil update status');
                }else{
                    return back()->with('error','Gagal update status');
                }
            }
            
            // update berdasarkan id
            $update = Sub_menu::where("id", $id)->update([
                'status' => 1,
            ]);

            // Kondisi Data Berhasil atau Gagal
            if($update){
                return back()->with('success','Berhasil update status');
            }else{
                return back()->with('error','Gagal update status');
            }

        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function destroy(Request $request, $id)
    {
        try {
            $delete = Sub_menu::where('id',$id)->delete();

            if($delete){
                return back()->with('success','Berhasil hapus Sub Menu');
            }else{
                return back()->with('error','Gagal hapus Sub Menu');
            }

        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
        
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'menu_id' => 'required',
                'nama' => 'required|max:255',
                'url' => 'required|max:255',
                'type_menu' => 'required|max:255',
                'icon' => 'required|max:255',
                'status' => 'required|max:255',
                
                
            ],[
                'menu_id.required' => 'menu id harus diisi',
                'nama.required' => 'nama harus diisi',
                'nama.max' => 'nama maksimal 255 karakter',
                'url.required' => 'url harus diisi',
                'url.max' => 'url maksimal 255 karakter',
                'type_menu.required' => 'type menu harus diisi',
                'type_menu.max' => 'type menu maksimal 255 karakter',
                'icon.required' => 'icon harus diisi',
                'icon.max' => 'icon maksimal 255 karakter',
                'status.required' => 'status harus diisi',
                'status.max' => 'status maksimal 255 karakter',
            ]);

            // update berdasarkan id
            $update = Sub_menu::where("id", $id)->update([
                'menu_id' => $request->menu_id,
                'nama' => $request->nama,
                'url' => $request->url,
                'type_menu' => $request->type_menu,
                'icon' => $request->icon,
                'status' => $request->status,
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
        
            $type_menu = 'sub menu';
            $title = 'sub menu';

            $menu = Menu::get();
            $data = Sub_menu::select(
                    'sub_menus.*', 
                    'menus.menu as menu_name'
                )
                ->leftJoin('menus', 'sub_menus.menu_id', '=', 'menus.id')
                ->where('nama', 'like', '%' . $search . '%')
                ->orderBy('sub_menus.id', 'desc')
                ->paginate(10);

            return view('Pages.Admin.SubMenu.index', compact('data', 'menu', 'type_menu', 'title'));
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

            $data = Sub_menu::select(
                    'sub_menus.id',
                    'sub_menus.menu_id', 
                    'menus.menu as menu_name',
                    'sub_menus.nama',
                    'sub_menus.url',
                    'sub_menus.type_menu',
                    'sub_menus.icon',
                    DB::raw("CASE WHEN sub_menus.status = 1 THEN 'aktif' ELSE 'tidak aktif' END as status"),
                    'sub_menus.created_at',
                )
            ->leftJoin('menus', 'sub_menus.menu_id', '=', 'menus.id')
            ->whereDate('sub_menus.created_at', '>=', $request->tanggal_awal)
            ->whereDate('sub_menus.created_at', '<=', $request->tanggal_akhir)
            ->orderBy('id', 'desc')
            ->get();

            return Excel::download(new SubMenuExport($data), 'SubMenu.xlsx');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function filter(Request $request)
    {
        try {
            $type_menu = 'sub menu';
            $title = 'sub menu';
            
            $request->validate([
                'tanggal_awal' => 'required',
                'tanggal_akhir' => 'required',
            ],[
                'tanggal_awal.required' => 'tanggal awal harus diisi',
                'tanggal_akhir.required' => 'tanggal akhir harus diisi',
            ]);

            $menu = Menu::get();
            $data = Sub_menu::select(
                    'sub_menus.*', 
                    'menus.menu as menu_name'
                )
            ->leftJoin('menus', 'sub_menus.menu_id', '=', 'menus.id')
            ->whereDate('sub_menus.created_at', '>=', $request->tanggal_awal)
            ->whereDate('sub_menus.created_at', '<=', $request->tanggal_akhir)
            ->orderBy('id', 'desc')
            ->paginate(10);
            
            return view('Pages.Admin.SubMenu.index', compact('data', 'menu', 'type_menu', 'title'));

        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function index()
    {
        try {
            $type_menu = 'sub menu';
            $title = 'sub menu';
            $menu = Menu::get();
            $data = Sub_menu::select(
                    'sub_menus.*', 
                    'menus.menu as menu_name'
                )
                ->leftJoin('menus', 'sub_menus.menu_id', '=', 'menus.id')
                ->orderBy('sub_menus.id', 'desc')
                ->paginate(10);

            return view('Pages.Admin.SubMenu.index', compact('data', 'menu', 'type_menu', 'title'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'menu_id' => 'required',
                'nama' => 'required|max:255',
                'url' => 'required|max:255',
                'type_menu' => 'required|max:255',
                'icon' => 'required|max:255',
                'status' => 'required|max:255',
                
                
            ],[
                'menu_id.required' => 'menu id harus diisi',
                'nama.required' => 'nama harus diisi',
                'nama.max' => 'nama maksimal 255 karakter',
                'url.required' => 'url harus diisi',
                'url.max' => 'url maksimal 255 karakter',
                'type_menu.required' => 'type menu harus diisi',
                'type_menu.max' => 'type menu maksimal 255 karakter',
                'icon.required' => 'icon harus diisi',
                'icon.max' => 'icon maksimal 255 karakter',
                'status.required' => 'status harus diisi',
                'status.max' => 'status maksimal 255 karakter',
            ]);

            // Membuat form dari Request Form
            $insert = Sub_menu::create([
                'menu_id' => $request->menu_id,
                'nama' => $request->nama,
                'url' => $request->url,
                'type_menu' => $request->type_menu,
                'icon' => $request->icon,
                'status' => $request->status,
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


    
}
