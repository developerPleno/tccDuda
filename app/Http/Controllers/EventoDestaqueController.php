<?php

namespace App\Http\Controllers;

use App\Models\EventoDestaque;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Carbon\Carbon;

class EventoDestaqueController extends Controller
{
    // Listar todos os eventos em destaque
    public function index()
    {
        $eventosDestaque = EventoDestaque::with('evento')->get();  // Pega os eventos relacionados
        return response()->json($eventosDestaque);
    }

    // Marcar um evento como destaque
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'id_evento' => 'required|integer',
            'id_usuario' => 'required|integer',
        ]);

        $usuario = Usuario::find($validatedData['id_usuario']);

        // Verificar se o usuário existe
        if (!$usuario) {
            return response()->json(['message' => 'Usuário não encontrado'], 404);
        }

        // Verificar se o usuário tem um pacote ativo
        if ($usuario->pacoteAtivo()) {
            // Verificar quantos eventos já foram destacados no mês
            $eventosDestacados = EventoDestaque::where('id_usuario', $usuario->id)
                ->whereMonth('data_destaque', Carbon::now()->month)
                ->count();

            // Se o limite de eventos do pacote foi atingido
            if ($eventosDestacados >= $usuario->eventos_destaque_restantes) {
                return response()->json(['message' => 'Você já destacou todos os eventos permitidos pelo seu pacote neste mês.'], 403);
            }
        } else {
            // Verificar se o usuário já destacou um evento no mês, caso não tenha um pacote ativo
            $eventoDestaqueMes = EventoDestaque::where('id_usuario', $usuario->id)
                ->whereMonth('data_destaque', Carbon::now()->month)
                ->first();

            if ($eventoDestaqueMes) {
                return response()->json(['message' => 'Você já destacou um evento este mês.'], 403);
            }
        }

        // Criar o destaque para o evento
        $eventoDestaque = EventoDestaque::create([
            'id_evento' => $validatedData['id_evento'],
            'id_usuario' => $validatedData['id_usuario'],
            'data_destaque' => now(),
        ]);

        // Atualizar o número de eventos restantes do pacote, se o usuário tiver um pacote ativo
        if ($usuario->pacoteAtivo()) {
            $usuario->eventos_destaque_restantes -= 1;
            $usuario->save();
        }

        return response()->json($eventoDestaque, 201);  // Código 201 para criação bem-sucedida
    }

    // Remover o destaque de um evento
    public function destroy($id)
    {
        $eventoDestaque = EventoDestaque::find($id);

        if (!$eventoDestaque) {
            return response()->json(['message' => 'Evento em destaque não encontrado'], 404);
        }

        $eventoDestaque->delete();

        return response()->json(['message' => 'Evento em destaque removido com sucesso']);
    }

    public function show($id)
    {
        $eventoDestaque = EventoDestaque::with('evento')->find($id);

        if (!$eventoDestaque) {
            return response()->json(['message' => 'Evento em destaque não encontrado'], 404);
        }

        return response()->json($eventoDestaque);
    }

    // Exibir eventos em destaque na home page dos usuários comuns
    public function eventosDestaqueHome()
    {
        $eventosDestaque = EventoDestaque::whereMonth('data_destaque', Carbon::now()->month)
            ->with('evento')  // Relaciona os eventos com os dados do evento em destaque
            ->get();

        if ($eventosDestaque->isEmpty()) {
            return response()->json(['message' => 'Nenhum evento em destaque para este mês'], 404);
        }

        return response()->json($eventosDestaque);
    }
}
