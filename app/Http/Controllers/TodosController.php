<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use App\Models\User;
use Illuminate\Http\Request;

class TodosController extends Controller
{

    public function index()
    {
        if(auth()->user()->hasPermissionTo('Read blog')){
            $user = User::all();
            return response()->json(['status' => true, 'message' => 'Elemento Localizado', 'data' => $user], 200);
        }else{
            return 'error';
        }
    }

    public function saveTodo(Request $request)
    {
        $user = User::find($request->user_id);
        $todo = Todo::find($request->todo_id);

        $user->todo()->save($todo);
    }

    public function getTodos(Request $request)
    {
        $user = User::find($request->user_id);
        $todos = $user->todo;

        return response()->json(['status' => true, 'message' => 'Dato localizado', 'data' => $todos], 200);


    }
}
