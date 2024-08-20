<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;

class PermissionsController extends Controller
{
    public function index()
    {
        if(auth()->user()->hasPermissionTo('Create blog', 'Read blog', 'Update blog', 'Delete blog')){
            $user = User::all();
            return response()->json(['status' => true, 'message' => 'Elemento Localizado', 'data' => $user], 200);
        }else{
            return 'error';
        }
    }


    public function asignarPermiso(Request $request)
    {
        $user = User::find($request->user_id);

        if(is_null($user)){
            return response()->json(['status'=>false, 'message'=>'Usuario no encontrado'], 403);
        }

        $validate = Validator::make($request->all(),[
            'permission' => 'required|exists:permissions,name'
        ],
        [
            'required' => 'Texto requerido',
            'exists' => 'El permiso no existe'
        ]);

        if($validate->fails()){
            return response()->json([
                'status' => false,
                'message' => 'Dato invalido',
                'error' => $validate->errors()
            ], 400);
        }

        $permission = Permission::where('name', $request->permission)->first();
        $user->givePermissionTo($permission);

        $permissionNames = $user->getPermissionNames();

        return response()->json([
            'status' => true,
            'message' => 'Permiso asignado al usuario',
            'Permisos' => $permissionNames
        ], 200);

    }

    public function revokePermiso(Request $request)
    {
        $user = User::find($request->user_id);

        if(is_null($user)){
            return response()->json(['status' => false, 'message' => 'Usuario no encotrado'], 403);
        }

        $validate = Validator::make($request->all(),[
            'permission' => 'required|exist:permissions,name'
        ],
        [
            'required' => 'Texto requerido',
            'exists' => 'El permiso no existe'
        ]);

        if($validate->fails()){
            return response()->json([
                'status' => false,
                'message' => 'Dato invalido',
                'error' => $validate->errors()
            ], 400);
        }

        $permission = Permission::where('name', $request->permission)->first();
        $user->revokePermissionTo($permission);

        $permissionNames = $user->getPermissionNames();

        return response()->json([
            'status' => true,
            'message' => 'Permiso revocado del usuario',
            'Permisos' => $permissionNames
        ], 200);
    }
}
