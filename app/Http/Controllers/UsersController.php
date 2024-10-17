<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Menu;
use App\Exports\UsersExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;

class UsersController extends Controller
{
    public function accessMenuUpdate(Request $request, $role_id, $menu_id)
    {
        $result = DB::table('users_access_menus')
                ->where('role_id', $role_id)
                ->where('menu_id', $menu_id)
                ->first();
        
        if (!$result) {
            // Membuat form dari Request Form
            $usersAccess = DB::table('users_access_menus')->insert([
                    'role_id' => $role_id,
                    'menu_id' => $menu_id
                ]);

            if ($usersAccess) {
                return redirect('users/accessMenu/'.$role_id)->with('success','Berhasil di tambahkan');
            }else{
                return redirect('users/accessMenu/'.$role_id)->with('error','Gagal di tambahkan');
            }
            
        } else {
            $usersAccess = DB::table('users_access_menus')
                            ->where('role_id', $role_id)
                            ->where('menu_id', $menu_id)
                            ->delete();

            if ($usersAccess) {
                return redirect('users/accessMenu/'.$role_id)->with('success','Berhasil di di hapus');
            }else{
                return redirect('users/accessMenu/'.$role_id)->with('error','Gagal di di hapus');
            }
        }
         
    }

    public function accessMenu(Request $request, $id)
    {
        try {
            $type_menu = 'users access menu';
            $title = 'users access menu';
            $users = User::where('id', $id)->first();
            $menu = Menu::orderBy('id', 'desc')->get();
            return view('Pages.Admin.Users.accessMenu', compact('users', 'menu', 'type_menu', 'title'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function destroy(Request $request, $id)
    {
        try {
            $delete = User::where('id',$id)->delete();

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
                'name' => 'required',
                'email' => 'required|max:255',
                'password' => 'required|max:255',
                'status' => 'required',
                
                
            ],[
                'name.required' => 'name harus diisi',
                'name.max' => 'name maksimal 255 karakter',
                'email.required' => 'email harus diisi',
                'email.max' => 'email maksimal 255 karakter',
                'password.required' => 'password harus diisi',
                'password.max' => 'password maksimal 255 karakter',
                'status.required' => 'status harus diisi',
            ]);

            // update berdasarkan id
            $update = User::where("id", $id)->update([
                'name' => $request->name,
                'email' => $request->email,
                'email_verified_at' => now(),
                'password' => Hash::make($request->password),
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
        
            $type_menu = 'users';
            $title = 'users';

            $data = User::select('*')
                ->where('name', 'like', '%' . $search . '%')
                ->orderBy('id', 'desc')
                ->paginate(10);

            return view('Pages.Admin.Users.index', compact('data', 'type_menu', 'title'));
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

            $data = User::select(
                'name',
                'email',
                DB::raw("CASE WHEN users.status = 1 THEN 'Admin' ELSE 'Operator ' END as status"),
                'created_at',
                )
            ->whereDate('created_at', '>=', $request->tanggal_awal)
            ->whereDate('created_at', '<=', $request->tanggal_akhir)
            ->orderBy('id', 'desc')
            ->get();

            return Excel::download(new UsersExport($data), 'Users.xlsx');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function filter(Request $request)
    {
        try {
            $type_menu = 'users';
            $title = 'users';
            
            $request->validate([
                'tanggal_awal' => 'required',
                'tanggal_akhir' => 'required',
            ],[
                'tanggal_awal.required' => 'tanggal awal harus diisi',
                'tanggal_akhir.required' => 'tanggal akhir harus diisi',
            ]);

            $data = User::select('*')
            ->whereDate('created_at', '>=', $request->tanggal_awal)
            ->whereDate('created_at', '<=', $request->tanggal_akhir)
            ->orderBy('id', 'desc')
            ->paginate(10);
            
            return view('Pages.Admin.Users.index', compact('data', 'type_menu', 'title'));

        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required',
                'email' => 'required|max:255',
                'password' => 'required|max:255',
                'status' => 'required',
                
                
            ],[
                'name.required' => 'name harus diisi',
                'name.max' => 'name maksimal 255 karakter',
                'email.required' => 'email harus diisi',
                'email.max' => 'email maksimal 255 karakter',
                'password.required' => 'password harus diisi',
                'password.max' => 'password maksimal 255 karakter',
                'status.required' => 'status harus diisi',
            ]);

            // Membuat form dari Request Form
            $insert = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'email_verified_at' => now(),
                'password' => Hash::make($request->password),
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

    public function index()
    {
        try {
            $type_menu = 'users';
            $title = 'users';
            $data = User::orderBy('id', 'desc')->paginate(10);
            return view('Pages.Admin.Users.index', compact('data', 'type_menu', 'title'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
