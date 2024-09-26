<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Confirmado extends Model
{
    use HasFactory;
    protected $primaryKey = "id";
    protected $table = "confirmados";
    protected $fillable = [
        'id_usuario',
        'id_evento',
        'data_confirmacao'
    ] ;
}
