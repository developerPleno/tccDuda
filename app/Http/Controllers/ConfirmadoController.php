<?php

namespace App\Http\Controllers;

use App\Models\Confirmado;
use Illuminate\Http\Request;

class ConfirmadoController extends Controller
{
    // Listar todos os usuários confirmados em um evento específico
    public function index($id_evento)
    {
        $confirmados = Confirmado::where('id_evento', $id_evento)->get();

        if ($confirmados->isEmpty()) {
            return response()->json(['message' => 'Nenhum usuário confirmado para este evento'], 404);
        }

        return response()->json($confirmados);
    }

    // Registrar confirmação de um usuário para um evento
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'id_usuario' => 'required|integer',
            'id_evento' => 'required|integer',
        ]);

        // Verificar se o usuário já está confirmado para o evento
        $confirmacaoExistente = Confirmado::where('id_usuario', $validatedData['id_usuario'])
            ->where('id_evento', $validatedData['id_evento'])
            ->first();

        if ($confirmacaoExistente) {
            return response()->json(['message' => 'Usuário já está confirmado para este evento'], 400);
        }

        // Registrar a confirmação de presença
        $confirmado = Confirmado::create([
            'id_usuario' => $validatedData['id_usuario'],
            'id_evento' => $validatedData['id_evento'],
            'data_confirmacao' => now(),
        ]);

        return response()->json($confirmado, 201);  // Código 201 para criação bem-sucedida
    }

    // Remover a confirmação de um usuário para um evento
    public function destroy($id_usuario, $id_evento)
    {
        $confirmado = Confirmado::where('id_usuario', $id_usuario)
            ->where('id_evento', $id_evento)
            ->first();

        if (!$confirmado) {
            return response()->json(['message' => 'Confirmação não encontrada'], 404);
        }

        $confirmado->delete();

        return response()->json(['message' => 'Confirmação removida com sucesso']);
    }

    // Contar o número de usuários confirmados para um evento específico
    public function contarConfirmados($id_evento)
    {
        $count = Confirmado::where('id_evento', $id_evento)->count();

        return response()->json(['confirmados' => $count]);
    }
}
