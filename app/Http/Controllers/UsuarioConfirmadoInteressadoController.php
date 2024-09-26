<?php

namespace App\Http\Controllers;

use App\Models\UsuarioConfirmadoInteressado;
use Illuminate\Http\Request;

class UsuarioConfirmadoInteressadoController extends Controller
{
    // Listar todos os registros de usuários confirmados/interessados
    public function index()
    {
        $usuariosConfirmadosInteressados = UsuarioConfirmadoInteressado::all();
        return response()->json($usuariosConfirmadosInteressados);
    }

    // Exibir um registro específico por ID
    public function show($id)
    {
        $registro = UsuarioConfirmadoInteressado::find($id);

        if (!$registro) {
            return response()->json(['message' => 'Registro não encontrado'], 404);
        }

        return response()->json($registro);
    }

    // Criar um novo registro de usuário interessado ou confirmado
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'id_evento' => 'required|integer',
            'id_usuario' => 'required|integer',
            'status' => 'required|in:interessado,confirmado', // Verificação para os status 'interessado' ou 'confirmado'
            'data' => 'required|date',
        ]);

        $registro = UsuarioConfirmadoInteressado::create($validatedData);

        return response()->json($registro, 201); // Retorna o código 201 de sucesso
    }

    // Atualizar um registro existente
    public function update(Request $request, $id)
    {
        $registro = UsuarioConfirmadoInteressado::find($id);

        if (!$registro) {
            return response()->json(['message' => 'Registro não encontrado'], 404);
        }

        $validatedData = $request->validate([
            'id_evento' => 'sometimes|integer',
            'id_usuario' => 'sometimes|integer',
            'status' => 'sometimes|in:interessado,confirmado',
            'data' => 'sometimes|date',
        ]);

        $registro->update($validatedData);

        return response()->json($registro);
    }

    // Deletar um registro
    public function destroy($id)
    {
        $registro = UsuarioConfirmadoInteressado::find($id);

        if (!$registro) {
            return response()->json(['message' => 'Registro não encontrado'], 404);
        }

        $registro->delete();

        return response()->json(['message' => 'Registro deletado com sucesso']);
    }
}
