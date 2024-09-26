<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\PreferenciaUsuario;


class Usuario extends Model
{
    use HasFactory;
    protected $primaryKey = 'id';
    protected $table = 'usuarios';
    protected $fillable = [
        'username',
        'email',
        'senha',
        'cidade',
        'tipo_usuario',
        'foto_perfil',
        'data_criacao'
    ];

    public function preferenciasUsuario (){
        return $this->hasMany(PreferenciaUsuario::class,'id','id_usuario');
    }
}
