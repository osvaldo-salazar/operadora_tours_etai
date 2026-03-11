<?php

namespace App\Http\Controllers;

// Facades de Laravel para base de datos, correo y utilidades
use Illuminate\Support\Facades\DB;     // Permite ejecutar consultas a la base de datos
use Illuminate\Support\Facades\Mail;   // Permite enviar correos electrónicos
use Illuminate\Support\Str;            // Utilidades para manejo de strings (como generar tokens)
use Illuminate\Http\Request;           // Manejo de datos enviados desde formularios

class PasswordResetController extends Controller
{

    // Muestra el formulario donde el usuario escribe su correo para recuperar contraseña
    public function requestForm(){

        // Retorna la vista donde el usuario introduce su correo
        return view('auth.recuperar_contra');

    }

    // Procesa el formulario y envía el enlace de recuperación
    public function sendResetLink(Request $request){

        // Valida que el campo email venga y tenga formato de correo
        $request->validate([
            'email' => 'required|email'
        ]);

        // Busca al usuario en la tabla usuarios por el correo ingresado
        $usuario = DB::table('usuarios')
        ->where('correo_usuario',$request->email)
        ->first();

        // Si el correo no existe en el sistema
        if(!$usuario){

            // Regresa al formulario con mensaje de error
            return back()->with('error','Correo no registrado');
        }

        // Genera un token aleatorio de 60 caracteres
        // Este token será parte del link para cambiar la contraseña
        $token = Str::random(60);

        // Inserta o actualiza el registro en la tabla password_resets
        DB::table('password_resets')->updateOrInsert(

            // Si existe el correo lo actualiza
            ['email'=>$request->email],

            // Si no existe lo crea
            [
            'token'=>$token,
            'created_at'=>now() // Guarda la fecha para controlar expiración
            ]

        );

        // Genera el link que recibirá el usuario en su correo
        $link = url("/reset-password/$token");

        // Envía el correo utilizando una vista Blade como plantilla
        Mail::send('emails.reset_password', ['link'=>$link], function($msg) use ($request){

            // Destinatario
            $msg->to($request->email)

            // Asunto del correo
            ->subject("Recuperación de contraseña");

        });

        // Regresa al formulario con mensaje de éxito
        return back()->with('success','Correo enviado con instrucciones');

    }


    // Muestra el formulario donde el usuario ingresará la nueva contraseña
    public function resetForm($token){

        // Busca el token en la tabla password_resets
        $registro = DB::table('password_resets')
        ->where('token',$token)
        ->first();

        // Si el token no existe
        if(!$registro){

            // Redirige al login con error
            return redirect('/login')->with('error','Link inválido');
        }

        // Verifica si el link ya expiró (más de 30 minutos)
        if(now()->diffInMinutes($registro->created_at) > 30){

            // Redirige al login si el link expiró
            return redirect('/login')->with('error','El link expiró');
        }

        // Si todo está correcto muestra la vista para crear nueva contraseña
        return view('auth.nueva_contra',[

            // Se envía el token a la vista para usarlo en el formulario
            'token'=>$token

        ]);

    }


    // Procesa el cambio de contraseña
    public function updatePassword(Request $request)
    {

        // Valida los datos enviados desde el formulario
        $request->validate([

            'token' => 'required',            // Token obligatorio
            'password' => 'required|min:6|confirmed' // Contraseña mínima 6 caracteres y confirmación

        ]);

        // Busca el token en la tabla password_resets
        $registro = DB::table('password_resets')
        ->where('token',$request->token)
        ->first();

        // Si el token no existe
        if(!$registro){

            // Redirige al login con error
            return redirect('/login')->with('error','Token inválido o expirado');
        }

        // Actualiza la contraseña del usuario
        $update = DB::table('usuarios')
        ->where('correo_usuario',$registro->email)
        ->update([

            // Se guarda la contraseña encriptada
            'pass_usuario' => password_hash($request->password, PASSWORD_DEFAULT)

        ]);

        // Si la actualización fue exitosa
        if($update){

            // Se elimina el token para que no pueda reutilizarse
            DB::table('password_resets')
            ->where('email',$registro->email)
            ->delete();

            // Redirige al login con mensaje de éxito
            return redirect('/login')->with('success','Contraseña cambiada correctamente');
        }

        // Si algo falló al actualizar
        return back()->with('error','No se pudo actualizar la contraseña');

    }

}