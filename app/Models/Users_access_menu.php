<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Users_access_menu extends Model
{
    use HasFactory;
    protected $table = 'users_access_menus';

    protected $fillable = [
        'role_id',
        'menu_id',
    ];
}
