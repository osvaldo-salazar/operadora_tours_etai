<?php

// Importa el sistema de rutas de Laravel
use Illuminate\Support\Facades\Route;

// Permite acceder a variables de sesión
use Illuminate\Support\Facades\Session;

// Controladores que manejan autenticación y recuperación de contraseña
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PasswordResetController;


/*
|--------------------------------------------------------------------------
| LOGIN
|--------------------------------------------------------------------------
| Rutas relacionadas con el inicio y cierre de sesión
|
*/

// Muestra la vista del login
Route::get('/login', [AuthController::class,'loginView'])->name('login');

// Procesa el formulario de login
Route::post('/login', [AuthController::class,'login']);

// Cierra la sesión del usuario
Route::get('/logout', [AuthController::class,'logout']);


/*
|--------------------------------------------------------------------------
| VERIFICACIÓN POR CÓDIGO
|--------------------------------------------------------------------------
| Segunda capa de seguridad (verificación enviada al correo)
|
*/

// Muestra la vista donde el usuario introduce el código recibido por correo
Route::get('/verificar', function(){

    return view('auth.verificar');

});

// Procesa el código ingresado por el usuario
Route::post('/verificar', [AuthController::class,'verificarCodigo']);

// Permite reenviar el código de verificación al correo
Route::post('/reenviar-codigo',[AuthController::class,'reenviarCodigo']);   


/*
|--------------------------------------------------------------------------
| DASHBOARD
|--------------------------------------------------------------------------
| Página principal del sistema después del login
|
*/

Route::get('/dashboard', function(){

    // Verifica si el usuario tiene una sesión activa
    if(!Session::has('usuario')){

        // Si no tiene sesión, lo redirige al login
        return redirect('/login');

    }

    // Si tiene sesión activa, muestra el dashboard
    return view('index');

});


/*
|--------------------------------------------------------------------------
| RECUPERAR CONTRASEÑA
|--------------------------------------------------------------------------
| Flujo completo de recuperación de contraseña
|
*/

// Muestra el formulario para ingresar el correo
Route::get('/recuperar-contra', [PasswordResetController::class, 'requestForm'])
    ->name('password.request');

// Procesa el formulario y envía el enlace al correo
Route::post('/recuperar-contra', [PasswordResetController::class, 'sendResetLink'])
    ->name('password.email');


// Ruta que recibe el token enviado por correo
// Muestra el formulario para crear una nueva contraseña
Route::get('/reset-password/{token}', [PasswordResetController::class,'resetForm']);


// Procesa el cambio de contraseña
Route::post('/reset-password', [PasswordResetController::class,'updatePassword']);


/*
|--------------------------------------------------------------------------
| HOME
|--------------------------------------------------------------------------
| Controla qué ocurre cuando el usuario entra a la raíz del sitio
|
*/

Route::get('/', function () {

    // Si el usuario ya está logueado
    if(Session::has('usuario')){

        // Lo redirige al dashboard
        return redirect('/dashboard');

    }

    // Si no está logueado lo envía al login
    return redirect('/login');

});