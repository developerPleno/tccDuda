<?php

namespace App\Http\Controllers;

use App\Models\TagDivulgador;
use Illuminate\Http\Request;

class TagDivulgadorController extends Controller
{
    // Listar todas as tags (tanto para divulgadores quanto para usuários comuns)
    public function index()
    {
        $tags = TagDivulgador::all();
        return response()->json($tags);
    }

    // Exibir uma tag específica por ID
    public function show($id)
    {
        $tag = TagDivulgador::find($id);

        if (!$tag) {
            return response()->json(['message' => 'Tag não encontrada'], 404);
        }

        return response()->json($tag);
    }

    // Criar uma nova tag (somente para divulgadores)
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'id_divulgador' => 'required|integer',
            'tag' => 'required|string|max:255',
        ]);

        $tag = TagDivulgador::create($validatedData);

        return response()->json($tag, 201); // Código 201 indica criação bem-sucedida
    }

    // Atualizar uma tag existente (somente divulgadores)
    public function update(Request $request, $id)
    {
        $tag = TagDivulgador::find($id);

        if (!$tag) {
            return response()->json(['message' => 'Tag não encontrada'], 404);
        }

        $validatedData = $request->validate([
            'tag' => 'sometimes|string|max:255',
        ]);

        $tag->update($validatedData);

        return response()->json($tag);
    }

    // Deletar uma tag (somente para divulgadores)
    public function destroy($id)
    {
        $tag = TagDivulgador::find($id);

        if (!$tag) {
            return response()->json(['message' => 'Tag não encontrada'], 404);
        }

        $tag->delete();

        return response()->json(['message' => 'Tag deletada com sucesso']);
    }

    // Listar todas as tags criadas por um divulgador específico
    public function tagsPorDivulgador($id_divulgador)
    {
        $tags = TagDivulgador::where('id_divulgador', $id_divulgador)->get();

        if ($tags->isEmpty()) {
            return response()->json(['message' => 'Nenhuma tag encontrada para este divulgador'], 404);
        }

        return response()->json($tags);
    }
}
