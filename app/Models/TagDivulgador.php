<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TagDivulgador extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';
    protected $table = 'tags_divulgadores';
    protected $fillable = [
        'id_divulgador',
        'tag',
    ];

    // Relação muitos-para-muitos com PreferenciaUsuario
    public function preferencias()
    {
        return $this->belongsToMany(PreferenciaUsuario::class, 'preferencias_usuarios_tags_divulgadores', 'tag_divulgador_id', 'preferencia_usuario_id');
    }
}
