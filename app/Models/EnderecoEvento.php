<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnderecoEvento extends Model
{
    use HasFactory;
    
    protected $primaryKey = 'id';
    protected $table = 'enderecos_eventos';
    protected $fillable = [
        'id_evento',
        'endereco',
        'cidade',
        'latitude',
        'longitude',
    ];

}
