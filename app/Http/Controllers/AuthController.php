<?php

namespace App\Http\Controllers;

// Importación de clases necesarias de Laravel
use Illuminate\Http\Request; // Manejo de datos enviados desde formularios
use Illuminate\Support\Facades\DB; // Permite realizar consultas a la base de datos
use Illuminate\Support\Facades\Session; // Manejo de sesiones del usuario
use Illuminate\Support\Facades\Mail; // Envío de correos electrónicos
use Illuminate\Support\Facades\RateLimiter; // Controla el número de intentos (protección contra ataques)
use Illuminate\Support\Str; // Utilidades para manejo de texto

class AuthController extends Controller
{

    // Muestra la vista del login
    public function loginView()
    {
        return view('login'); // Retorna la vista login.blade.php
    }


    // Función que procesa el login del usuario
    public function login(Request $request)
    {

        // Se crea una clave única para el RateLimiter usando:
        // usuario ingresado + IP del usuario
        // Esto permite limitar intentos de login por usuario e IP
        $key = Str::lower($request->usuario).'|'.$request->ip();


        // Verifica si el usuario ha superado los intentos permitidos (5)
        if (RateLimiter::tooManyAttempts($key, 5)) {

            // Obtiene cuantos segundos faltan para poder intentar nuevamente
            $seconds = RateLimiter::availableIn($key);

            // Regresa al login con mensaje de error
            return back()->with('error', "Demasiados intentos. Intenta nuevamente en $seconds segundos.");
        }


        // Validación de los campos enviados desde el formulario
        $request->validate([
            'usuario' => 'required', // Usuario o correo obligatorio
            'password' => 'required' // Contraseña obligatoria
        ]);


        // Consulta en la base de datos buscando:
        // - usuario por nombre de usuario
        // - o por correo electrónico
        $usuario = DB::table('usuarios')
        ->where(function($query) use ($request){

            // Busca si coincide el usuario
            $query->where('usuario_usuario', $request->usuario)

                  // O si coincide el correo
                  ->orWhere('correo_usuario', $request->usuario);

        })

        // Solo permite usuarios activos
        ->where('estado_usuario', 1)

        // Obtiene el primer resultado
        ->first();


        // Si no existe el usuario
        if(!$usuario){

            // Registra el intento fallido en el RateLimiter
            RateLimiter::hit($key,60);

            // Retorna mensaje de error
            return back()->with('error','Usuario o correo no encontrado');

        }


        // Verifica si la contraseña ingresada coincide con la guardada en la base de datos
        if(!password_verify($request->password,$usuario->pass_usuario)){

            // Registra intento fallido
            RateLimiter::hit($key,60);

            // Retorna error
            return back()->with('error','Contraseña incorrecta');

        }


        // Genera un código de verificación de 6 dígitos
        $codigo = rand(100000,999999);


        // Guarda datos temporales en sesión
        Session::put('codigo_verificacion',$codigo); // Código enviado al correo
        Session::put('usuario_temp',$usuario->usuario_usuario); // Usuario temporal
        Session::put('codigo_expira', now()->addMinutes(5)); // Tiempo límite del código
        Session::put('intentos_codigo',0); // Contador de intentos para ingresar el código


        // Envía el código al correo del usuario
        Mail::raw("Tu código de verificación es: $codigo", function($msg) use ($usuario){

            $msg->to($usuario->correo_usuario) // Destinatario
            ->subject("Código de verificación"); // Asunto del correo

        });


        // Registra el acceso en la tabla de auditoría del sistema
        DB::table('accesos_sistema')->insert([
            'usuario' => $usuario->usuario_usuario, // Usuario que intenta ingresar
            'ip' => $request->ip(), // Dirección IP
            'fecha' => now() // Fecha y hora
        ]);


        // Redirige a la vista donde el usuario debe ingresar el código
        return redirect('/verificar');

    }



    // Función que verifica el código enviado al correo
    public function verificarCodigo(Request $request)
    {

        // Verifica si el código ya expiró
        if(now()->greaterThan(Session::get('codigo_expira'))){

            // Limpia toda la sesión
            Session::flush();

            // Redirige al login con mensaje de error
            return redirect('/login')->with('error','El código expiró');

        }


        // Obtiene la cantidad de intentos que lleva el usuario
        $intentos = Session::get('intentos_codigo');


        // Si supera los 3 intentos permitidos
        if($intentos >= 3){

            // Se cierra la sesión
            Session::flush();

            // Redirige al login
            return redirect('/login')->with('error','Demasiados intentos');

        }


        // Verifica si el código ingresado coincide con el enviado
        if($request->codigo == Session::get('codigo_verificacion')){


            // Se crea la sesión oficial del usuario logueado
            Session::put('usuario',Session::get('usuario_temp'));


            // Se eliminan los datos temporales
            Session::forget('codigo_verificacion');
            Session::forget('usuario_temp');
            Session::forget('codigo_expira');
            Session::forget('intentos_codigo');


            // Redirige al dashboard del sistema
            return redirect('/dashboard');

        }


        // Si el código es incorrecto aumenta el contador de intentos
        Session::put('intentos_codigo',$intentos+1);

        // Retorna error
        return back()->with('error','Código incorrecto');

    }



    // Función que permite reenviar el código de verificación
    public function reenviarCodigo()
    {

        // Busca nuevamente el usuario que está en sesión temporal
        $usuario = DB::table('usuarios')
        ->where('usuario_usuario',Session::get('usuario_temp'))
        ->first();


        // Genera un nuevo código
        $codigo = rand(100000,999999);


        // Actualiza datos de sesión
        Session::put('codigo_verificacion',$codigo);
        Session::put('codigo_expira', now()->addMinutes(5));
        Session::put('intentos_codigo',0);


        // Envía nuevamente el código al correo
        Mail::raw("Tu nuevo código de verificación es: $codigo", function($msg) use ($usuario){

            $msg->to($usuario->correo_usuario)
            ->subject("Nuevo código de verificación");

        });


        // Retorna mensaje de éxito
        return back()->with('success','Código reenviado');

    }



    // Función para cerrar sesión
    public function logout()
    {

        // Elimina todos los datos de sesión
        Session::flush();

        // Redirige al login
        return redirect('/login');

    }

}