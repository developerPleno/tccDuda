<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\UsuarioConfirmadoInteressadoController;
use App\Http\Controllers\TagDivulgadorController;
use App\Http\Controllers\PreferenciaUsuarioController;
use App\Http\Controllers\EventoController;
use app\http\controllers\InteressadoController;
use App\Http\Controllers\EnderecoEventoController;
use app\Http\Controllers\ConfirmadoController;
use App\Http\Controllers\EventoDestaqueController;

Route::prefix('api')->group(function () {
  Route::get('/usuarios', [UsuarioController::class, 'index']);
  Route::get('/usuarios/{id}', [UsuarioController::class, 'show']);
  Route::post('/usuarios', [UsuarioController::class, 'store']);
  Route::put('/usuarios/{id}', [UsuarioController::class, 'update']);
  Route::delete('/usuarios/{id}', [UsuarioController::class, 'destroy']);


  Route::get('/usuarios-confirmados-interessados', [UsuarioConfirmadoInteressadoController::class, 'index']);
  Route::get('/usuarios-confirmados-interessados/{id}', [UsuarioConfirmadoInteressadoController::class, 'show']);
  Route::post('/usuarios-confirmados-interessados', [UsuarioConfirmadoInteressadoController::class, 'store']);
  Route::put('/usuarios-confirmados-interessados/{id}', [UsuarioConfirmadoInteressadoController::class, 'update']);
  Route::delete('/usuarios-confirmados-interessados/{id}', [UsuarioConfirmadoInteressadoController::class, 'destroy']);

  Route::get('/tags', [TagDivulgadorController::class, 'index']);
  Route::get('/tags/{id}', [TagDivulgadorController::class, 'show']);
  Route::post('/tags', [TagDivulgadorController::class, 'store']);
  Route::put('/tags/{id}', [TagDivulgadorController::class, 'update']);
  Route::delete('/tags/{id}', [TagDivulgadorController::class, 'destroy']);
  Route::get('/tags/divulgador/{id_divulgador}', [TagDivulgadorController::class, 'tagsPorDivulgador']);

  Route::get('/preferencias/{id_usuario}', [PreferenciaUsuarioController::class, 'index']);
  Route::post('/preferencias', [PreferenciaUsuarioController::class, 'store']);
  Route::put('/preferencias/{id}', [PreferenciaUsuarioController::class, 'update']);
  Route::delete('/preferencias/{id}', [PreferenciaUsuarioController::class, 'destroy']);
  Route::get('/eventos/preferencias/{id_usuario}', [PreferenciaUsuarioController::class, 'eventosBaseadosEmPreferencias']);

  Route::get('/eventos', [EventoController::class, 'index']);
  Route::get('/eventos/{id}', [EventoController::class, 'show']);
  Route::post('/eventos', [EventoController::class, 'store']);
  Route::put('/eventos/{id}', [EventoController::class, 'update']);
  Route::delete('/eventos/{id}', [EventoController::class, 'destroy']);
  Route::get('/eventos/preferencias/{id_usuario}', [EventoController::class, 'eventosBaseadosEmPreferencias']);
  Route::get('/eventos/{id_evento}/interessados-confirmados', [EventoController::class, 'interessadosEConfirmados']);
  Route::post('/eventos/{id_evento}/marcar-interesse', [EventoController::class, 'marcarInteresseOuConfirmacao']);

  Route::get('/eventos/{id_evento}/interessados', [InteressadoController::class, 'index']);
  Route::post('/eventos/{id_evento}/interessados', [InteressadoController::class, 'store']);
  Route::delete('/eventos/{id_evento}/interessados/{id_usuario}', [InteressadoController::class, 'destroy']);
  Route::get('/eventos/{id_evento}/contar-interessados', [InteressadoController::class, 'contarInteressados']);

  Route::get('/eventos-destaque', [EventoDestaqueController::class, 'index']);
  Route::get('/eventos-destaque/{id}', [EventoDestaqueController::class, 'show']);
  Route::post('/eventos-destaque', [EventoDestaqueController::class, 'store']);
  Route::delete('/eventos-destaque/{id}', [EventoDestaqueController::class, 'destroy']);
  Route::get('/eventos-destaque/home', [EventoDestaqueController::class, 'eventosDestaqueHome']);

  Route::get('/enderecos', [EnderecoEventoController::class, 'index']);
  Route::get('/enderecos/{id_evento}', [EnderecoEventoController::class, 'show']);
  Route::post('/enderecos', [EnderecoEventoController::class, 'store']);
  Route::put('/enderecos/{id}', [EnderecoEventoController::class, 'update']);
  Route::delete('/enderecos/{id}', [EnderecoEventoController::class, 'destroy']);
  Route::get('/enderecos/{id_evento}/mapa', [EnderecoEventoController::class, 'showMapa']);

  Route::get('/eventos/{id_evento}/confirmados', [ConfirmadoController::class, 'index']);
  Route::post('/eventos/{id_evento}/confirmados', [ConfirmadoController::class, 'store']);
  Route::delete('/eventos/{id_evento}/confirmados/{id_usuario}', [ConfirmadoController::class, 'destroy']);
  Route::get('/eventos/{id_evento}/contar-confirmados', [ConfirmadoController::class, 'contarConfirmados']);
});
