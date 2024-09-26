<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PreferenciaUsuario extends Model
{
    use HasFactory;
    
    protected $primaryKey = 'id';
    protected $table = 'preferencias_usuarios';
    protected $fillable = [
        'id_usuario',
        'estilo',
        
    ];
}
