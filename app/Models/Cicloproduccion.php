<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cicloproduccion extends Model
{
     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'dias' ,'inicio', 'fin',
    ];
}
