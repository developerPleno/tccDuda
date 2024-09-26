<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsuarioConfirmadoInteressado extends Model
{
    use HasFactory;
    
    protected $primaryKey = 'id';
    protected $table = 'usuarios_confirmados_interessados';
    protected $fillable = [
        'id_evento','id_usuario','status','data',
    ];
}
