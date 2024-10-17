<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sub_menu extends Model
{
    use HasFactory;
    protected $table = 'sub_menus';

    protected $fillable = [
        'menu_id',
        'nama',
        'url',
        'type_menu',
        'icon',
        'status'
    ];
}
