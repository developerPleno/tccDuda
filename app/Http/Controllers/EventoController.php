<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use App\Models\PreferenciaUsuario;
use App\Models\UsuarioConfirmadoInteressado;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class EventoController extends Controller
{
    // Listar todos os eventos
    public function index(): JsonResponse
    {
        $eventos = Evento::all();
        return response()->json($eventos);
    }

    // Exibir um evento específico por ID
    public function show($id): JsonResponse
    {
        $evento = Evento::find($id);

        if (!$evento) {
            return response()->json(['message' => 'Evento não encontrado'], 404);
        }

        return response()->json($evento);
    }

    // Criar um novo evento
    public function store(Request $request): JsonResponse
    {
        $estilosPermitidos = ['sertanejo', 'rock', 'funk', 'eletronica', 'reggae', 'pop'];

        $validatedData = $request->validate([
            'nome_evento' => 'required|string|max:255',
            'descricao' => 'required|string',
            'data_evento' => 'required|date',
            'hora_inicio' => 'required|string',
            'valor_entrada' => 'required|numeric',
            'endereco' => 'required|string|max:255',
            'cidade' => 'required|string|max:255',
            'estilo' => 'required|string|in:' . implode(',', $estilosPermitidos),  // O estilo deve ser um dos permitidos
            'foto_divulgacao' => 'nullable|string',
            'destaque' => 'required|boolean',
            'id_divulgador' => 'required|integer',
        ]);

        $evento = Evento::create($validatedData);

        return response()->json($evento, 201);
    }

    // Atualizar um evento existente
    public function update(Request $request, $id): JsonResponse
    {
        $evento = Evento::find($id);

        if (!$evento) {
            return response()->json(['message' => 'Evento não encontrado'], 404);
        }

        $validatedData = $request->validate([
            'nome_evento' => 'sometimes|string|max:255',
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

        $evento->update($validatedData);

        return response()->json($evento);
    }

    // Deletar um evento
    public function destroy($id): JsonResponse
    {
        $evento = Evento::find($id);

        if (!$evento) {
            return response()->json(['message' => 'Evento não encontrado'], 404);
        }

        $evento->delete();

        return response()->json(['message' => 'Evento deletado com sucesso']);
    }

    // Listar eventos baseados nas preferências de estilo de um usuário
    public function eventosBaseadosEmPreferencias($id_usuario): JsonResponse
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
    public function interessadosEConfirmados($id_evento): JsonResponse
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
    public function marcarInteresseOuConfirmacao(Request $request, $id_evento): JsonResponse
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
            // Atualizar o status de interesse ou confirmação
            $registroExistente->update([
                'status' => $validatedData['status'],
                'data' => now(),  // Atualizar a data para a mudança de status
            ]);
    
            return response()->json(['message' => 'Status atualizado com sucesso', 'registro' => $registroExistente], 200);
        }
    
        // Criar o registro de interesse ou confirmação se não existir
        $registro = UsuarioConfirmadoInteressado::create([
            'id_evento' => $id_evento,
            'id_usuario' => $validatedData['id_usuario'],
            'status' => $validatedData['status'],
            'data' => now(),
        ]);
    
        return response()->json($registro, 201);
    }    
}
