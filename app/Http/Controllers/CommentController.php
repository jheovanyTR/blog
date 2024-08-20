<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if(auth()->user()->hasPermissionTo('Read blog')){
            $comment= Comment::all();
            return response()->json(['status' => true, 'message' => 'Elemento Localizado', 'data' => $comment], 200);
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

        $data = Comment::create($request->all());

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
        $data = Comment::find($id);

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
        $data = Comment::find($id);

        if(is_null($data))
            return response()->json(['status' => false, 'message' => 'Dato no encotrado'], 401);

        $data->delete();

        return response()->json(['status' => true, 'message' => 'Dato eliminado correctamente'], 200);
    }
}
