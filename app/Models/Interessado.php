<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Interessado extends Model
{
    use HasFactory;
    
    protected $primaryKey = 'id';
    protected $table = 'interessados';
    protected $fillable = [
        'id_usuario',
        'id_evento',
        'data_interesse',
    ];
}
