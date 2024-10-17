<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\PreferenciaUsuario;
use Illuminate\Database\Eloquent\SoftDeletes;



class Usuario extends Model
{
    use HasFactory, SoftDeletes;
    protected $primaryKey = 'id';
    public $timestamps = false;
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

    protected $dates = ['deleted_at'];  // Adiciona o campo deleted_at às datas que serão manipuladas

    public function preferenciasUsuario (){
        return $this->hasMany(PreferenciaUsuario::class,'id','id_usuario');
    }
}
