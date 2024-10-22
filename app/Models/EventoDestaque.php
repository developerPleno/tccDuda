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
        'id_usuario',  // Adicionar o campo id_usuario ao fillable
        'data_destaque',
    ];

    public function evento()
    {
        return $this->belongsTo(Evento::class, 'id_evento');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario');
    }

    public function podeDestacarEvento($usuario)
    {
        if ($usuario->pacote_ativo) {
            // Verifica a quantidade de destaques restantes, dependendo do plano
            $destaquesEsteMes = self::where('id_usuario', $usuario->id)
                ->whereMonth('data_destaque', now()->month)
                ->count();

            if ($usuario->pacote_ativo == '3_destaques' && $destaquesEsteMes < 3) {
                return true;
            }

            if ($usuario->pacote_ativo == '5_destaques' && $destaquesEsteMes < 5) {
                return true;
            }

            if ($usuario->pacote_ativo == '10_destaques' && $destaquesEsteMes < 10) {
                return true;
            }
        }

        // Caso o usuário não tenha plano ativo, verifica se já tem um destaque no mês
        return self::where('id_usuario', $usuario->id)
            ->whereMonth('data_destaque', now()->month)
            ->count() == 0;
    }
}
