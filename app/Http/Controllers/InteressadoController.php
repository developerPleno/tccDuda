<?php

namespace App\Http\Controllers;

use App\Models\Interessado;
use Illuminate\Http\Request;

class InteressadoController extends Controller
{
    // Listar todos os usuários interessados em um evento específico
    public function index($id_evento)
    {
        $interessados = Interessado::where('id_evento', $id_evento)->get();

        if ($interessados->isEmpty()) {
            return response()->json(['message' => 'Nenhum interessado encontrado para este evento'], 404);
        }

        return response()->json($interessados);
    }

    // Registrar interesse de um usuário em um evento
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'id_usuario' => 'required|integer',
            'id_evento' => 'required|integer',
        ]);

        // Verificar se o usuário já demonstrou interesse neste evento
        $interesseExistente = Interessado::where('id_usuario', $validatedData['id_usuario'])
            ->where('id_evento', $validatedData['id_evento'])
            ->first();

        if ($interesseExistente) {
            return response()->json(['message' => 'Usuário já demonstrou interesse neste evento'], 400);
        }

        // Registrar o interesse
        $interessado = Interessado::create([
            'id_usuario' => $validatedData['id_usuario'],
            'id_evento' => $validatedData['id_evento'],
            'data_interesse' => now(),
        ]);

        return response()->json($interessado, 201); // Código 201 indica criação bem-sucedida
    }

    // Deletar o interesse de um usuário em um evento
    public function destroy($id_usuario, $id_evento)
    {
        $interessado = Interessado::where('id_usuario', $id_usuario)
            ->where('id_evento', $id_evento)
            ->first();

        if (!$interessado) {
            return response()->json(['message' => 'Interesse não encontrado'], 404);
        }

        $interessado->delete();

        return response()->json(['message' => 'Interesse removido com sucesso']);
    }

    // Contar o número de interessados em um evento específico
    public function contarInteressados($id_evento)
    {
        $count = Interessado::where('id_evento', $id_evento)->count();

        return response()->json(['interessados' => $count]);
    }
}
