<?php

namespace App\Http\Middleware;

// Importa la clase Closure para permitir continuar la ejecución de la petición
use Closure;

// Importa la clase Request que contiene los datos de la petición HTTP
use Illuminate\Http\Request;

// Permite acceder a las variables de sesión
use Illuminate\Support\Facades\Session;

class AuthMiddleware
{
    // Método principal del middleware que intercepta cada petición
    public function handle(Request $request, Closure $next)
    {

        // Verifica si en la sesión existe la variable "usuario"
        // Esta variable se crea cuando el usuario inicia sesión correctamente
        if (!Session::has('usuario')) {

            // Si no existe la sesión significa que el usuario no está autenticado
            // Entonces se redirige automáticamente al login
        return redirect('/login')->with('error','Debe iniciar sesión');
        }

        // Si la sesión existe, permite continuar con la ejecución de la ruta
        return $next($request);
    }
}