<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use App\Models\PreferenciaUsuario;
use App\Models\UsuarioConfirmadoInteressado;
use Illuminate\Http\Request;

class EventoController extends Controller
{
    // Listar todos os eventos
    public function index()
    {
        $eventos = Evento::all();
        return response()->json($eventos);
    }

    // Exibir um evento específico por ID
    public function show($id)
    {
        $evento = Evento::find($id);

        if (!$evento) {
            return response()->json(['message' => 'Evento não encontrado'], 404);
        }

        return response()->json($evento);
    }

    // Criar um novo evento
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nome_evento' => 'required|string|max:255',
            'descricao' => 'required|string',
            'data_evento' => 'required|date',
            'hora_inicio' => 'required|string',
            'valor_entrada' => 'required|numeric',
            'endereco' => 'required|string|max:255',
            'cidade' => 'required|string|max:255',
            'estilo' => 'required|string|max:255',
            'foto_divulgacao' => 'nullable|string',
            'destaque' => 'required|boolean',
        ]);

        $evento = Evento::create($validatedData);

        return response()->json($evento, 201);
    }

    // Atualizar um evento existente
    public function update(Request $request, $id)
    {
        dd($id);
        $evento = Evento::find($id);

        if (!$evento) {
            return response()->json(['message' => 'Evento não encontrado'], 404);
        }

        $validatedData = $request->validate([
            'nome_evento' => 'required|string|max:255',
            'descricao' => 'sometimes|string',
            'data_evento' => 'sometimes|date',
            'hora_inicio' => 'sometimes|string',
            'valor_entrada' => 'sometimes|numeric',
            'endereco' => 'sometimes|string|max:255',
            'cidade' => 'sometimes|string|max:255',
            'estilo' => 'sometimes|string|max:255',
            'foto_divulgacao' => 'nullable|string',
            'destaque' => 'sometimes|boolean',
        ]);

        $fill = [
            ''
        ];
        $evento->update($fill);

        return response()->json($evento);
    }

    // Deletar um evento
    public function destroy($id)
    {
        $evento = Evento::find($id);

        if (!$evento) {
            return response()->json(['message' => 'Evento não encontrado'], 404);
        }

        $evento->delete();

        return response()->json(['message' => 'Evento deletado com sucesso']);
    }

    // Listar eventos baseados nas preferências de estilo de um usuário
    public function eventosBaseadosEmPreferencias($id_usuario)
    {
        // Obter as preferências de estilo do usuário
        $preferencias = PreferenciaUsuario::where('id_usuario', $id_usuario)->pluck('estilo');

        // Pesquisar todos os eventos que correspondem aos estilos preferidos do usuário
        $eventos = Evento::whereIn('estilo', $preferencias)->get();

        if ($eventos->isEmpty()) {
            return response()->json(['message' => 'Nenhum evento encontrado para as preferências do usuário'], 404);
        }

        return response()->json($eventos);
    }

    // Obter o número de interessados e confirmados em um evento específico
    public function interessadosEConfirmados($id_evento)
    {
        $interessados = UsuarioConfirmadoInteressado::where('id_evento', $id_evento)
            ->where('status', 'interessado')
            ->count();

        $confirmados = UsuarioConfirmadoInteressado::where('id_evento', $id_evento)
            ->where('status', 'confirmado')
            ->count();

        return response()->json([
            'interessados' => $interessados,
            'confirmados' => $confirmados,
        ]);
    }

    // Marcar um usuário como interessado ou confirmado em um evento
    public function marcarInteresseOuConfirmacao(Request $request, $id_evento)
    {
        $validatedData = $request->validate([
            'id_usuario' => 'required|integer',
            'status' => 'required|in:interessado,confirmado',
        ]);

        // Verificar se o usuário já está registrado como interessado ou confirmado
        $registroExistente = UsuarioConfirmadoInteressado::where('id_evento', $id_evento)
            ->where('id_usuario', $validatedData['id_usuario'])
            ->first();

        if ($registroExistente) {
            return response()->json(['message' => 'Usuário já está marcado como interessado ou confirmado'], 400);
        }

        // Criar o registro de interesse ou confirmação
        $registro = UsuarioConfirmadoInteressado::create([
            'id_evento' => $id_evento,
            'id_usuario' => $validatedData['id_usuario'],
            'status' => $validatedData['status'],
            'data' => now(),
        ]);

        return response()->json($registro, 201);
    }
}
