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
        'estilos', // Agora armazenará múltiplos estilos
        'tags_preferencia'
    ];

    protected $casts = [
        'estilos' => 'array',  // Converte o campo para array
    ];

    // Relação muitos-para-muitos com TagDivulgador
    public function tags()
    {
        return $this->belongsToMany(TagDivulgador::class, 'preferencias_usuarios_tags_divulgadores', 'preferencia_usuario_id', 'tag_divulgador_id');
    }
}
