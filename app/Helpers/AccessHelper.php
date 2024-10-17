<?php

namespace App\Helpers;

use App\Models\User;
use App\Models\Users_access_menu;
use Illuminate\Support\Facades\DB;

class AccessHelper {

    public static function check_access($role_id, $menu_id) {
        $result = DB::table('users_access_menus')
                ->where('role_id', $role_id)
                ->where('menu_id', $menu_id)
                ->get();

        if ($result->count() > 0) {
            return "checked";
        }
    }
}
