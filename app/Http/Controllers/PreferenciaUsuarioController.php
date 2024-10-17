<?php

namespace App\Http\Controllers;

use App\Models\PreferenciaUsuario;
use App\Models\TagDivulgador;
use App\Models\Evento; // Adicione esta linha para importar o Model Evento
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PreferenciaUsuarioController extends Controller
{
    // Função para listar as preferências de um usuário específico
    public function index($id_usuario): JsonResponse
    {
        $preferencias = PreferenciaUsuario::where('id_usuario', $id_usuario)->with('tags')->get();

        if ($preferencias->isEmpty()) {
            return response()->json(['message' => 'Nenhuma preferência encontrada para este usuário.'], 404);
        }

        return response()->json($preferencias);
    }

    // Função para criar preferências de um usuário
    public function store(Request $request): JsonResponse
    {
        // Validação dos dados recebidos
        $validatedData = $request->validate([
            'id_usuario' => 'required|integer',
            'estilos' => 'required|array|max:2', // Agora aceita múltiplos estilos (máximo 2)
            'tags_preferencia' => 'nullable|array', // Pode ser nulo ou um array de IDs de tags
        ]);

        // Se o campo 'tags_preferencia' não estiver vazio
        if (!empty($validatedData['tags_preferencia'])) {
            // Verificar se as tags existem na tabela de TagDivulgador
            $tagsExistentes = TagDivulgador::whereIn('id', $validatedData['tags_preferencia'])->pluck('id')->toArray();

            // Se as tags preferidas não existirem na tabela de tags dos divulgadores
            if (count($tagsExistentes) !== count($validatedData['tags_preferencia'])) {
                return response()->json(['message' => 'Uma ou mais tags escolhidas não existem'], 400);
            }
        } else {
            // Se não houver tags, defina $tagsExistentes como vazio
            $tagsExistentes = [];
        }

        // Criar ou atualizar as preferências do usuário
        $preferencia = PreferenciaUsuario::updateOrCreate(
            ['id_usuario' => $validatedData['id_usuario']],
            ['estilos' => json_encode($validatedData['estilos'])] // Agora salva estilos como um array
        );

        // Sincronizar as tags (preferências) escolhidas com o usuário, se houver tags
        $preferencia->tags()->sync($tagsExistentes);

        // Retornar o resultado com as tags sincronizadas
        return response()->json($preferencia->tags, 201);
    }

    // Função para atualizar as preferências de um usuário existente
    public function update(Request $request, $id): JsonResponse
    {
        $preferencia = PreferenciaUsuario::find($id);

        if (!$preferencia) {
            return response()->json(['message' => 'Preferência não encontrada.'], 404);
        }

        // Validação dos dados recebidos
        $validatedData = $request->validate([
            'estilos' => 'required|array|max:2', // Permite até dois estilos
            'tags_preferencia' => 'nullable|array', // Pode ser nulo ou um array de IDs de tags
        ]);

        // Se o campo 'tags_preferencia' não estiver vazio
        if (!empty($validatedData['tags_preferencia'])) {
            // Verificar se as tags existem na tabela de TagDivulgador
            $tagsExistentes = TagDivulgador::whereIn('id', $validatedData['tags_preferencia'])->pluck('id')->toArray();

            // Se as tags preferidas não existirem na tabela de tags dos divulgadores
            if (count($tagsExistentes) !== count($validatedData['tags_preferencia'])) {
                return response()->json(['message' => 'Uma ou mais tags escolhidas não existem'], 400);
            }
        } else {
            // Se não houver tags, defina $tagsExistentes como vazio
            $tagsExistentes = [];
        }

        // Atualizar a preferência do usuário
        $preferencia->update([
            'estilos' => json_encode($validatedData['estilos']) // Agora salva estilos como um array
        ]);

        // Sincronizar as tags (preferências) escolhidas com o usuário, se houver tags
        $preferencia->tags()->sync($tagsExistentes);

        return response()->json($preferencia);
    }

    // Função para deletar uma preferência de usuário
    public function destroy($id): JsonResponse
    {
        $preferencia = PreferenciaUsuario::find($id);

        if (!$preferencia) {
            return response()->json(['message' => 'Preferência não encontrada.'], 404);
        }

        // Excluir as tags associadas e a própria preferência
        $preferencia->tags()->detach();
        $preferencia->delete();

        return response()->json(['message' => 'Preferência excluída com sucesso.']);
    }

    // Função para listar eventos baseados nas preferências de estilo de um usuário
    public function eventosBaseadosEmPreferencias($id_usuario): JsonResponse
    {
        // Obter as preferências de estilo do usuário
        $preferencias = PreferenciaUsuario::where('id_usuario', $id_usuario)->pluck('estilos');

        // Converter o campo estilos de JSON para array
        $estilosArray = [];
        foreach ($preferencias as $estilos) {
            $estilosArray = array_merge($estilosArray, json_decode($estilos, true));
        }

        // Pesquisar todos os eventos que correspondem aos estilos preferidos do usuário
        $eventos = Evento::whereIn('estilo', $estilosArray)->get();

        if ($eventos->isEmpty()) {
            return response()->json(['message' => 'Nenhum evento encontrado para as preferências do usuário'], 404);
        }

        return response()->json($eventos);
    }
}
