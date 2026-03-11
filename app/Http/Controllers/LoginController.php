<?php

// Inicia la sesión para poder usar variables de sesión
session_start();

// Incluye el modelo de usuario que contiene las consultas a la base de datos
require_once "../models/UsuarioModel.php";

// Se define la clase que controla el proceso de login
class LoginController {

    // Método estático que ejecuta el proceso de autenticación
    public static function login() {

    
    // Función para registrar acciones en la bitácora del sistema
    function registrarBitacora($usuario,$accion){

        // Inserta un registro en la tabla bitacora
        DB::table('bitacora')->insert([
        'usuario'=>$usuario, // usuario que realiza la acción
        'accion'=>$accion,   // acción realizada
        'tabla'=>'usuarios', // tabla relacionada con la acción
        
        ]);


    }


    // Verifica si se enviaron los datos del formulario
    if(isset($_POST["usuario"]) && isset($_POST["password"])){

            // Guarda los datos enviados por el formulario
            $usuario = $_POST["usuario"];
            $password = $_POST["password"];

            // Llama al modelo para buscar el usuario en la base de datos
            $respuesta = UsuarioModel::login($usuario);

            // Si el usuario existe
            if($respuesta){

                // Verifica si la contraseña ingresada coincide con la almacenada
                if(password_verify($password, $respuesta["pass_usuario"])){

                    // Guarda datos del usuario en sesión
                    $_SESSION["usuario"] = $respuesta["usuario_usuario"];
                    $_SESSION["nombre"] = $respuesta["nombre_usuario"];

                    //REGISTRA EL ACCIONES  EN LA BITÁCORA
                    registrarBitacora($usuario->usuario_usuario,'login exitoso');

                    // Redirige al dashboard del sistema
                    header("Location: ../dashboard.php");
                    

                }else{

                    registrarBitacora($usuario->usuario_usuario,'login fallido');

                    // Si la contraseña es incorrecta
                    echo "Contraseña incorrecta";

                }

            }else{

                // Si el usuario no existe
                echo "Usuario no encontrado";

            }

        }

    }

}

// Ejecuta el método login automáticamente cuando se carga este archivo
LoginController::login();