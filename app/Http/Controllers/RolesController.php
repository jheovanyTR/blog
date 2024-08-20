<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesController extends Controller
{
    public function index()
    {
        if(auth()->user()->hasPermissionTo('Update blog')){
            $user = User::all();
            return response()->json(['status' => true, 'message' => 'Elemento Localizado', 'data' => $user], 200);
        }else{
            return 'error';
        }
    }

    public function asignarRol(Request $request)
    {
        $user = User::find($request->user_id);

        if(is_null($user)){
            return response()->json(['status'=>false, 'message'=>'Usuario no encontrado'], 403);
        }

        $validate = Validator::make($request->all(),[
            'role' => 'required|exists:roles,name'
        ],
        [
            'required' => 'Texto requerido',
            'exists' => 'El rol no existe'
        ]);

        if($validate->fails()){
            return response()->json([
                'status' => false,
                'message' => 'Dato invalido',
                'error' => $validate->errors()
            ], 400);
        }

        $role = Role::where('name', $request->role)->first();
        $user->assignRole($role);

        $roleNames = $user->getRoleNames();

        return response()->json([
            'status' => true,
            'message' => 'Rol asignado al usuario',
            'Roles' => $roleNames
        ], 200);

    }

    public function revokeRol(Request $request)
    {
        $user = User::find($request->user_id);

        if(is_null($user)){
            return response()->json(['status' => false, 'message' => 'Usuario no encotrado'], 403);
        }

        $validate = Validator::make($request->all(),[
            'role' => 'required|exist:roles,name'
        ],
        [
            'required' => 'Texto requerido',
            'exists' => 'El rol no existe'
        ]);

        if($validate->fails()){
            return response()->json([
                'status' => false,
                'message' => 'Dato invalido',
                'error' => $validate->errors()
            ], 400);
        }

        $role = Role::where('name', $request->role)->first();
        $user->removeRole($role);

        $rolNames = $user->getRoleNames();

        return response()->json([
            'status' => true,
            'message' => 'Permiso revocado del usuario',
            'Permisos' => $rolNames
        ], 200);
    }
}
