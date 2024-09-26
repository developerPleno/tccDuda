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
}
