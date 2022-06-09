<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class files extends Model
{
    protected $fillable = [
        'name', 'id_user', 'file'
    ];
}
