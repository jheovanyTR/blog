<?php

namespace App\Http\Controllers;

use App\Models\Prueba;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PruebaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $prueba = Prueba::all();
        return response()->json(['status'=>true, 'message'=> 'Elemento localizado', 'data' => $prueba], 200);
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
        $validate = Validator::make($request->all(), ['text' => 'required'], ['required' => 'Se requiere texto']);


        if($validate->fails())
            return response()->json([
                'status' => false,
                'message' => 'Dato invalido',
                'error' => $validate->errors()
            ], 400);

        $data = Prueba::create($request->all());

        return response()->json(['status'=>true, 'message'=> 'Elemento creado', 'data' => $data], 201);


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
        $data = Prueba::find($id);  //encontrar el dato por id, en el modelo

        if(is_null($data)) //por si no hay dato manda error ;   php puro
            return response()->json(['status' => false, 'message' => 'Dato no encontrado'], 401);

        $data->fill($request->all());
        $data->update();    //actualiza el dato

        return response()->json(['status' => true, 'message' => 'Dato actualizado'], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $data = Prueba::find($id);

        if(is_null($data))
            return response(['status' => false, 'message' => 'Dato no encontrado'], 401);

        $data->delete();

        return response()->json(['status' => true, 'message' => 'Dato eliminado correctamente'], 200);
    }
}
