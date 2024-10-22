<?php

namespace App\Http\Controllers;

use App\Models\TagDivulgador;
use App\Models\Usuario;
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

        // Verificar se o divulgador existe e se ele tem permissão (tipo_usuario = divulgador)
        $divulgador = Usuario::find($validatedData['id_divulgador']);

        if (!$divulgador || $divulgador->tipo_usuario !== 'divulgador') {
            return response()->json(['message' => 'Somente usuários com permissão de divulgador podem criar tags.'], 403);
        }

        // Verificar se a tag tem apenas uma palavra
        if (str_word_count($validatedData['tag']) > 1) {
            return response()->json(['message' => 'A tag deve ser apenas uma palavra.'], 400);
        }

        // Criar a tag usando save() manual
        $tag = new TagDivulgador();
        $tag->id_divulgador = $validatedData['id_divulgador'];
        $tag->tag = $validatedData['tag'];
        $tag->save();

        return response()->json($tag, 201); // Código 201 indica criação bem-sucedida
    }

    // Atualizar uma tag existente (somente o divulgador que criou a tag)
    public function update(Request $request, $id)
    {
        $tag = TagDivulgador::find($id);

        if (!$tag) {
            return response()->json(['message' => 'Tag não encontrada'], 404);
        }

        // Verificar se o usuário é o mesmo que criou a tag
        if ($request->input('id_divulgador') != $tag->id_divulgador) {
            return response()->json(['message' => 'Acesso negado. Somente o divulgador que criou a tag pode atualizá-la.'], 403);
        }

        $validatedData = $request->validate([
            'tag' => 'sometimes|string|max:255|regex:/^\w+$/', // Garantir que a tag seja apenas uma palavra
        ]);

        $tag->update($validatedData);

        return response()->json($tag);
    }

    // Deletar uma tag (somente o divulgador que criou a tag)
    public function destroy($id)
    {
        $tag = TagDivulgador::find($id);

        if (!$tag) {
            return response()->json(['message' => 'Tag não encontrada'], 404);
        }

        // Verificar se o usuário é o mesmo que criou a tag
        if (request()->input('id_divulgador') != $tag->id_divulgador) {
            return response()->json(['message' => 'Acesso negado. Somente o divulgador que criou a tag pode deletá-la.'], 403);
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
