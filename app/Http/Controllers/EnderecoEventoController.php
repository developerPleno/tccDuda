<?php

namespace App\Http\Controllers;

use App\Models\EnderecoEvento;
use Illuminate\Http\Request;

class EnderecoEventoController extends Controller
{
    // Listar todos os endereços de eventos
    public function index()
    {
        $enderecos = EnderecoEvento::all();
        return response()->json($enderecos);
    }

    // Exibir o endereço de um evento específico por ID do evento
    public function show($id_evento)
    {
        $endereco = EnderecoEvento::where('id_evento', $id_evento)->first();

        if (!$endereco) {
            return response()->json(['message' => 'Endereço do evento não encontrado'], 404);
        }

        return response()->json($endereco);
    }

    // Criar um novo endereço de evento
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'id_evento' => 'required|integer',
            'endereco' => 'required|string|max:255',
            'cidade' => 'required|string|max:255',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $endereco = EnderecoEvento::create($validatedData);

        return response()->json($endereco, 201);  // Código 201 para criação bem-sucedida
    }

    // Atualizar o endereço de um evento existente
    public function update(Request $request, $id)
    {
        $endereco = EnderecoEvento::find($id);

        if (!$endereco) {
            return response()->json(['message' => 'Endereço não encontrado'], 404);
        }

        $validatedData = $request->validate([
            'endereco' => 'sometimes|string|max:255',
            'cidade' => 'sometimes|string|max:255',
            'latitude' => 'sometimes|numeric',
            'longitude' => 'sometimes|numeric',
        ]);

        $endereco->update($validatedData);

        return response()->json($endereco);
    }

    // Deletar o endereço de um evento
    public function destroy($id)
    {
        $endereco = EnderecoEvento::find($id);

        if (!$endereco) {
            return response()->json(['message' => 'Endereço não encontrado'], 404);
        }

        $endereco->delete();

        return response()->json(['message' => 'Endereço deletado com sucesso']);
    }

    // Exibir o endereço no mapa (pode ser implementado com uma integração com um serviço de mapas)
    public function showMapa($id_evento)
    {
        $endereco = EnderecoEvento::where('id_evento', $id_evento)->first();

        if (!$endereco) {
            return response()->json(['message' => 'Endereço do evento não encontrado'], 404);
        }

        // Supondo que você integre com um serviço de mapas, como Google Maps, você pode gerar a URL do mapa aqui.
        $mapUrl = "https://www.google.com/maps/search/?api=1&query={$endereco->latitude},{$endereco->longitude}";

        return response()->json(['map_url' => $mapUrl]);
    }
}
