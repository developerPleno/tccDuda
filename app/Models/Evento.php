<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evento extends Model
{
    use HasFactory;

    // Especificar a tabela associada ao modelo
    protected $table = 'eventos';

    // Especificar a chave primária
    protected $primaryKey = 'id';

    // Definir os campos que podem ser preenchidos em massa (mass assignment)
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
    ];

    // Laravel gerencia automaticamente created_at e updated_at
    public $timestamps = true;

    // Definir a relação com o modelo Usuario (assumindo que id_divulgador seja uma foreign key)
    public function divulgador()
    {
        return $this->belongsTo(Usuario::class, 'id_divulgador', 'id');
    }
}
