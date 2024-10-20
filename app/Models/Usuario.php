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
        'genero',
        'data_nascimento',
        'pacote_destaque_ativo',
        'tipo_usuario',
        'foto_perfil',
        'data_criacao',
        'pacote_destaque_ativo',
        'eventos_destaque_restantes', // Quantidade de destaques permitidos no mês
        'data_expiracao_pacote', // Quando o pacote expira
    ];

    // Função para verificar se o pacote de destaques está ativo
    public function pacoteAtivo()
    {
        return $this->pacote_destaque_ativo && $this->data_expiracao_pacote >= now();
    }

    protected $dates = ['deleted_at'];  // Adiciona o campo deleted_at às datas que serão manipuladas

    public function preferenciasUsuario (){
        return $this->hasMany(PreferenciaUsuario::class,'id','id_usuario');
    }
}
