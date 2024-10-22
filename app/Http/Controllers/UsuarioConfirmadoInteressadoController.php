<?php

namespace App\Http\Controllers;

use App\Models\UsuarioConfirmadoInteressado;
use App\Models\Evento;
use Illuminate\Http\Request;

class UsuarioConfirmadoInteressadoController extends Controller
{
    // Função para criar um novo registro de usuário interessado ou confirmado
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'id_evento' => 'required|integer',
            'id_usuario' => 'required|integer',
            'status' => 'required|in:interessado,confirmado',
        ]);

        // Adiciona a data e hora atual automaticamente
        $validatedData['data'] = now();

        // Cria o registro com os dados validados
        $registro = UsuarioConfirmadoInteressado::create($validatedData);

        // Atualizar a contagem na tabela de eventos
        $evento = Evento::find($validatedData['id_evento']);

        if ($validatedData['status'] === 'confirmado') {
            $evento->increment('usuarios_confirmados');
        } elseif ($validatedData['status'] === 'interessado') {
            $evento->increment('usuarios_interessados');
        }

        return response()->json($registro, 201); // Código 201 indica criação bem-sucedida
    }

    // Função para atualizar um registro de usuário interessado ou confirmado
    public function update(Request $request, $id)
    {
        $registro = UsuarioConfirmadoInteressado::find($id);

        if (!$registro) {
            return response()->json(['message' => 'Registro não encontrado'], 404);
        }

        $validatedData = $request->validate([
            'status' => 'required|in:interessado,confirmado',
        ]);

        $evento = Evento::find($registro->id_evento);

        // Atualizar contagem de interessados e confirmados com base na alteração do status
        if ($registro->status !== $validatedData['status']) {
            if ($registro->status === 'interessado' && $validatedData['status'] === 'confirmado') {
                // Decrementar interessados e incrementar confirmados
                $evento->decrement('usuarios_interessados');
                $evento->increment('usuarios_confirmados');
            } elseif ($registro->status === 'confirmado' && $validatedData['status'] === 'interessado') {
                // Decrementar confirmados e incrementar interessados
                $evento->decrement('usuarios_confirmados');
                $evento->increment('usuarios_interessados');
            }
        }

        // Atualizar o registro com o novo status
        $registro->update($validatedData);

        return response()->json($registro);
    }
}
