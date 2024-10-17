<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
{
    // Função para listar todos os usuários
    public function index()
    {
        $usuarios = Usuario::all();
        return response()->json($usuarios);
    }

    // Função para exibir um único usuário por ID
    public function show($id)
    {
        $usuario = Usuario::find($id);

        if (!$usuario) {
            return response()->json(['message' => 'Usuário não encontrado'], 404);
        }

        return response()->json($usuario);
    }

    // Função para criar um novo usuário
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'username' => 'required|string|max:255',
            'email' => 'required|email|unique:usuarios,email',
            'senha' => 'required|string|min:6',
            'cidade' => 'required|string|max:255',
            'tipo_usuario' => 'required|string',
            'foto_perfil' => 'nullable|string',
        ]);

        // Hashing the password
        $validatedData['senha'] = Hash::make($validatedData['senha']);
        $validatedData['data_criacao'] = now();

        $usuario = Usuario::create($validatedData);

        return response()->json($usuario, 201); // Código 201 indica criação bem-sucedida
    }

    // Função para atualizar os dados de um usuário existente
    public function update(Request $request, $id)
    {
        $usuario = Usuario::find($id);

        if (!$usuario) {
            return response()->json(['message' => 'Usuário não encontrado'], 404);
        }

        $validatedData = $request->validate([
            'username' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:usuarios,email,' . $id,
            'senha' => 'sometimes|string|min:6',
            'cidade' => 'sometimes|string|max:255',
            'tipo_usuario' => 'sometimes|string',
            'foto_perfil' => 'nullable|string',
            'excluir_conta' => 'nullable|boolean',
        ]);

        // Atualizar senha se fornecida
        if (isset($validatedData['senha'])) {
            $validatedData['senha'] = Hash::make($validatedData['senha']);
        }

        // Se o usuário quer excluir a conta
        if (isset($validatedData['excluir_conta']) && $validatedData['excluir_conta']) {
            $usuario->delete();  // Isso criará um timestamp na coluna deleted_at (soft delete)
            return response()->json(['message' => 'Conta marcada como excluída'], 200);
        }

        $usuario->update($validatedData);

        return response()->json($usuario);
    }

    // Função para deletar um usuário permanentemente
    public function destroy(Request $request, $id)
    {
        $usuario = Usuario::withTrashed()->find($id);

        if (!$usuario) {
            return response()->json(['message' => 'Usuário não encontrado'], 404);
        }

        // Verifica se o usuário foi marcado para exclusão (soft delete)
        if ($usuario->trashed()) {
            $confirmacao = $request->input('confirmacao');  // Recebe a confirmação do usuário para deletar permanentemente

            if ($confirmacao && $confirmacao === 'sim') {
                $usuario->forceDelete();  // Excluir permanentemente
                return response()->json(['message' => 'Usuário excluído permanentemente'], 200);
            } else {
                return response()->json(['message' => 'Confirmação necessária para excluir permanentemente o usuário.'], 400);
            }
        }

        return response()->json(['message' => 'Este usuário ainda não foi marcado para exclusão.'], 400);
    }
}
