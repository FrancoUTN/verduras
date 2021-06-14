<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Hortaliza extends Model
{
    use SoftDeletes;

    protected $primaryKey = 'id';
    protected $table = 'hortaliza';
    public $incrementing = true;
    public $timestamps = false;

    const DELETED_AT = 'fechaBaja';

    protected $fillable = [
        'precio', 'nombre', 'foto', 'tipo', 'fechaBaja'
    ];
}