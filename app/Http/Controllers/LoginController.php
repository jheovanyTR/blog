<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

use function Pest\Laravel\delete;

class LoginController extends Controller
{
    public function index()
    {
        if(auth()->user()->hasPermissionTo('Create blog')){
            $user = User::all();
            return response()->json(['status' => true, 'message' => 'Elemento Localizado', 'data' => $user], 200);
        }else{
            return 'error';
        }
    }

    public function login(Request $request)
    {
        if(!Auth::attempt($request->only('email', 'password')))
        {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }

        $user = User::where('email', $request->email)->first();


        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Hi, ' . $user->name,
            'accessToken' => $token,
            'token_type' => 'Bearer',
            'user' => $user
        ], 200);

        /*
        $credentials = $request->only('email', 'password');

        $validate = Validator::make($credentials, [
            'email' => 'required',
            'password' => 'required'
        ]);

        if($validate->fails())
        {
            return response()->json()([
                'response' => 'false',
                'errors' => $validate->errors(),
                'code' => 200
            ], 200);
        }

        return response()->json(['status' => true, 'message' => 'Credenciales Correctas'], 201);
        */

    }

    public function register(Request $request)
    {
        $validate = Validator::make($request->all(),[
            'email' => 'required|string|unique:users',
            'password' => 'required',Password::min(8)->numbers()
        ]);

        if($validate->fails())
        {
            return response()->json($validate->errors(), 401);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)    //cifrado de contraseña
        ]);

        $token = $user->creteToken('auth_token')->plainTextToken;

        return response()->json([
            'data' => $user,
            'access_token' => $token,
            'token_type' => 'Bearer'
        ], 201);
    }

    public function logout(Request $request)
    {
        auth()->user()->tokens()->delete();

        return [
            'message' => 'You have successfully logged out and the token was successfully deleted'
        ];
    }
}
