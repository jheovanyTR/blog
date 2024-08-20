<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PermissionsController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProcedureController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PruebaController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\ToDoController;
use App\Http\Controllers\UserController;
use GuzzleHttp\Middleware;

/*Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
*/

Route::resource('prueba', PruebaController::class);
Route::get('login', [LoginController::class, 'login']);


//proteger rutas
Route::middleware(['auth:sanctum'])->group(function(){
    // Route::group(['middleware' => ['role:admin']], function(){
        // dd(auth()->user()->getRoleNames());
        Route::resource('user', UserController::class);
    // });
    Route::resource('todo', ToDoController::class);
    Route::get('user_todo', [UserController::class, 'saveTodo']);
    Route::get('todos', [UserController::class, 'getTodos']);
    Route::get('logout', [LoginController::class, 'logout']);
    Route::get('comment', [CommentController::class, 'store']);
    Route::get('post', [PostController::class, 'store']);
    Route::post('asignar', [PermissionsController::class, 'asignarPermiso']);
    Route::post('revocar', [PermissionsController::class, 'revokePermiso']);
    Route::post('asignarRol', [RolesController::class, 'asignarRol']);
    Route::post('getUser', [ProcedureController::class, 'getUser']);
    Route::post('getPost', [ProcedureController::class, 'getPost']);
});
