<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventoDestaque extends Model
{
    use HasFactory;
    
    protected $primaryKey = 'id';
    protected $table = 'eventos_destaque';
    protected $fillable = [
        'id_evento',
        'data_destaque',
    ];

    public function evento()
    {
        return $this->belongsTo(Evento::class, 'id_evento');
    }
}
