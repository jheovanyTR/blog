<?php

namespace App\Http\Controllers;

use App\Mail\WelcomeMail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;

class PasswordResetController extends Controller
{
    public function sendResetLinkEmail(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'email' => 'required|email|exists:users,email'
        ],
        [
            'required' => 'Se requiere el email',
            'email' => 'Debe ser válido',
            'exists' => 'No está registrado'
        ]);

        if($validator->fails())
            return response()->json([
                'status' => false,
                'message' => 'Datos Inválidos',
                'errors' => $validator->errors()
            ], 401);

        $pin = rand(100000, 999999);

        $password_reset = DB::table('password_reset_tokens')->insert([
            'email' => $request->email,
            'token' => $pin,
            'created_at' => Carbon::now('America/Mexico_City')
        ]);

        if(! $password_reset)
            return response()->json([
                'status' => false,
                'message' => 'Error al generar token, reintente por favor'
            ], 400);



        Mail::to($request->email)->send(new WelcomeMail($pin));

        return response()->json([
            'status' => true,
            'message' => 'Por favor revise su bandeja de entrada'
        ], 200);

    }

    public function verifyPin(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'email' => 'required|email|exists:users,email',
            'token' => 'required'
        ],
        [
            'required' => 'atributo es requerido',
            'email' => 'ingrese un correo válido',
            'exists' => 'No está registrado'
        ]);

        if($validator->fails())
            return response()->json([
                'status' => false,
                'message' => 'Datos inválidos',
                'errors' => $validator->errors()
            ], 401);

        $check = DB::table('password_reset_tokens')->where('email', $request->email)->where('token', $request->token);

        if($check->exists())
        {
            $difference = Carbon::now()->diffInSeconds($check->first()->created_at);
            if($difference > 3600)
                return response()->json([
                    'status' => false,
                    'message' => 'Token Expired'
                ], 400);
        }

        $delete = DB::table('password_reset_tokens')->where('email', $request->email)->where('token', $request->token)->delete();

        return response()->json([
            'status' => true,
            'message' => 'Ya puede cambiar su contraseña'
        ], 200);
    }

    public function actualizarContraseña(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'email' => 'required|email|exists:users,email',
            'new_password' => 'required'
        ],
        [
            'required' => 'El atributo es requerido',
            'email' => 'El correo electrónico proporcionado no es válido',
            'exists' => 'El correo electrónico no está registrado',


        ]);

        if($validator->fails())
            return response()->json([
                'status' => false,
                'message' => 'Datos invalidos',
                'errors' => $validator->fails()
            ], 401);


        $user = User::where('email', $request->email)->first();
        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json([
            'status' => true,
            'message' => 'Contraseña actualizada correctamente'
        ], 200);
    }

}
