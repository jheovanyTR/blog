<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if(auth()->user()->hasPermissionTo('Create blog', 'Read blog', 'Update blog', 'Delete blog')){
            $post = Post::all();
            return response()->json(['status' => true, 'message' => 'Elemento Localizado', 'data' => $post], 200);
        }else{
            return 'error';
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'title' => 'required',
            'body' => 'required',
        ],
        [
            'required' => 'Texto requerido'
        ]);

        if($validate->fails())
            return response()->json([
                'status' => false,
                'message' => 'Dato invalido',
                'error' => $validate->errors()
            ], 400);

        $data = Post::create($request->all());

        return response()->json(['status' => true, 'message' => 'Elemento creado', 'Data' => $data], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $data = Post::find($id);

        if(is_null($data))
            return response()->json(['status' => false, 'message' => 'Dato no encontrado'], 401);

        $data->fill($request->all());
        $data->update();

        return response()->json(['status' => true, 'message' => 'Dato actualizado'], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $data = Post::find($id);

        if(is_null($data))
            return response()->json(['status' => false, 'message' => 'Dato no encotrado'], 401);

        $data->delete();

        return response()->json(['status' => true, 'message' => 'Dato eliminado correctamente'], 200);
    }


    public function saveTodo(Request $request)
    {
        $comment = Comment::find($request->commet_id);
        $post = Post::find($request->post_id);

        $comment->post()->save($post);
    }

    public function getTodos(Request $request)
    {
        $comment = Comment::find($request->comment_id);
        $posts = $comment->post;

        return response()->json(['status' => true, 'message' => 'Dato localizado', 'data' => $posts], 200);


    }
}
