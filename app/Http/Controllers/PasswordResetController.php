<?php

namespace App\Http\Controllers;

use App\Mail\WelcomeMail;
use Carbon\Carbon;
use Illuminate\Auth\Passwords\PasswordBroker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

    public function sendTestEmail()
    {
        $htmlContent = '<!doctype html>
        <html>
          <head>
            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
          </head>
          <body style="font-family: sans-serif;">
            <h1>Congrats for sending test email with Mailtrap!</h1>
            <p>If you are viewing this email in your inbox – the integration works.</p>
            <p>Now send your email using our SMTP server and integration of your choice!</p>
            <p>Good luck! Hope it works.</p>
          </body>
        </html>';

        Mail::html($htmlContent, function ($message) {
            $message->to('to@example.com')
                    ->subject('You are awesome!')
                    ->from('from@example.com');
        });

        return 'Email sent!';
    }


}
