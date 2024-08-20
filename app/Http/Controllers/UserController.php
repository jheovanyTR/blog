<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        if(auth()->user()->hasAllDirectPermissions(['Create blog', 'Read blog', 'Update blog', 'Delete blog'])){
            $user = User::all();
            return response()->json(['status' => true, 'message' => 'Elemento Localizado', 'data' => $user], 200);
        }else{
            return 'error';

/*        $user = auth()->user();

        if($user->hasPermissionTo('Create blog') or
           $user->hasPermissionTo('Read blog') or
           $user->hasPermissionTo('Update blog') or
           $user->hasPermissionTo('Delete blog') ){

            $users = User::all();
            return response()->json(['status' => true, 'message' => 'Elemento Localizado', 'data' => $user], 200);
        }else{
            return response()->json(['status' => false, 'message' => 'No tienes los permisos necesarios'], 403);
        }*/
    }
                                    //para cada controlador asignar un permiso

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
            'name' => 'required',
            'email' => 'required|unique:users', //email unico
            'password' => ['required', Password::min(8)->numbers()] //la contraseña debe contener un minimo de 8 caracteres con al menos 1 numero
        ],
        [
            'required' => 'Texto requerido',
            'unique' => 'El email ya esta registrado'
        ]);

        if($validate->fails())
            return response()->json([
                'status' => false,
                'message' =>'Dato invalido',
                'error' => $validate->errors()
            ], 400);

        $data =User::create($request->all());

        return response()->json(['status' => true, 'message' => 'Elemento creado', 'data' => $data], 201);
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
        $data = User::find($id);

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
        $data = User::find($id);

        if(is_null($data))
            return response(['status' => false, 'message' => 'Dato no encontrado'], 401);

        $data->delete();

        return response()->json(['status' => true, 'message' => 'Dato eliminado correctamente'], 200);
    }


}
