<?php

namespace App\Http\Controllers;

use App\Models\PreferenciaUsuario;
use Illuminate\Http\Request;

class PreferenciaUsuarioController extends Controller
{
    // Listar todas as preferências de estilo de um usuário específico
    public function index($id_usuario)
    {
        $preferencias = PreferenciaUsuario::where('id_usuario', $id_usuario)->get();

        if ($preferencias->isEmpty()) {
            return response()->json(['message' => 'Nenhuma preferência encontrada para este usuário'], 404);
        }

        return response()->json($preferencias);
    }

    // Adicionar uma nova preferência de estilo para o usuário
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'id_usuario' => 'required|integer',
            'estilo' => 'required|string|max:255',
        ]);

        $preferencia = PreferenciaUsuario::create($validatedData);

        return response()->json($preferencia, 201); // Código 201 para criação bem-sucedida
    }

    // Atualizar uma preferência existente
    public function update(Request $request, $id)
    {
        $preferencia = PreferenciaUsuario::find($id);

        if (!$preferencia) {
            return response()->json(['message' => 'Preferência não encontrada'], 404);
        }

        $validatedData = $request->validate([
            'estilo' => 'sometimes|string|max:255',
        ]);

        $preferencia->update($validatedData);

        return response()->json($preferencia);
    }

    // Deletar uma preferência de estilo
    public function destroy($id)
    {
        $preferencia = PreferenciaUsuario::find($id);

        if (!$preferencia) {
            return response()->json(['message' => 'Preferência não encontrada'], 404);
        }

        $preferencia->delete();

        return response()->json(['message' => 'Preferência deletada com sucesso']);
    }

    // Listar eventos baseados nas preferências de estilo do usuário
    public function eventosBaseadosEmPreferencias($id_usuario)
    {
        // Aqui você pode implementar a lógica para buscar eventos baseados nas preferências de estilo do usuário.
        // Exemplo: Pesquisar todos os eventos que correspondem aos estilos preferidos do usuário.
        $preferencias = PreferenciaUsuario::where('id_usuario', $id_usuario)->pluck('estilo');

        // Vamos supor que você tenha um model de eventos (não fornecido aqui) e cada evento tenha um campo 'estilo'
        $eventos = EventoController::whereIn('estilo', $preferencias)->get();

        if ($eventos->isEmpty()) {
            return response()->json(['message' => 'Nenhum evento encontrado para as preferências do usuário'], 404);
        }

        return response()->json($eventos);
    }
}
