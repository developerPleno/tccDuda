<?php

namespace App\Http\Controllers;

use App\Models\EventoDestaque;
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
        ]);

        $dataAtual = Carbon::now()->startOfMonth();  // Data atual no início do mês

        // Verificar se já existe um evento em destaque para o mês atual
        $eventoExistente = EventoDestaque::where('data_destaque', '>=', $dataAtual)
            ->where('id_evento', $validatedData['id_evento'])
            ->first();

        if ($eventoExistente) {
            return response()->json(['message' => 'Já existe um evento em destaque neste mês'], 400);
        }

        // Adicionar o evento como destaque
        $eventoDestaque = EventoDestaque::create([
            'id_evento' => $validatedData['id_evento'],
            'data_destaque' => now(),
        ]);

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
