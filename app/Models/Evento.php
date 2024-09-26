<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evento extends Model
{
    use HasFactory;
    protected $primaryKey = 'id';
    protected $table = 'eventos';
    protected $fillable = [
        'nome_evento',
        'descricao',
        'data_evento',
        'hora_inicio',
        'valor_entrada',
        'cidade',
        'estilo',
        'foto_divulgacao',
        'id_divulgador',
        'usuarios_confirmados',
        'usuarios_interessados',
        'destaque',
        'data_criacao',
    ];
}
