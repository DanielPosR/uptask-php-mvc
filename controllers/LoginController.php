<?php

namespace Controllers;

use Classes\Email;
use Model\Usuario;
use MVC\Router;

class LoginController
{


    //* LOGIN / REGISTRO
    public static function login(Router $router)
    {
        $alertas = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $auth = new Usuario($_POST);

            $alertas = $auth->validarLogin();

            if (empty($alertas)) {

                //Verificar que el usuario exista en la BD
                $usuario = Usuario::where('email', $auth->email);

                if (!$usuario || !$usuario->confirmado) {
                    Usuario::setAlerta('error', 'El usuario no existe o no ha confirmado su cuenta');
                } else {

                    // El usuario existe
                    if (password_verify($_POST['password'], $usuario->password)) {

                        //iniciar la sesion
                        session_start();

                        $_SESSION['id'] = $usuario->id;
                        $_SESSION['nombre'] = $usuario->nombre;
                        $_SESSION['email'] = $usuario->email;
                        $_SESSION['login'] = true;

                        // Redireccionar al usuario
                        header('Location: /dashboard');
                    } else {
                        Usuario::setAlerta('error', 'Password incorrecto');
                    }
                }
            }
        }

        $alertas = Usuario::getAlertas();

        // Render a la vista
        $router->render('auth/login', [
            'titulo' => 'Iniciar sesión',
            'alertas' => $alertas
        ]);
    }




    //* CERRAR SESION
    public static function logout()
    {
        session_start();
        $_SESSION = [];

        header('Location: /');
    }




    //* CREAR CUENTA
    public static function crear(Router $router)
    {
        $alertas = [];

        $usuario = new Usuario;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $usuario->sincronizar($_POST);

            //* Valida los datos del formulario
            $alertas = $usuario->validarNuevaCuenta();


            //* Si todos los campos son correctos, valida que el usuario no exista
            if (empty($alertas)) {
                $existeUsuario = Usuario::where('email', $usuario->email);

                if ($existeUsuario) {
                    Usuario::setAlerta('error', 'El usuario ya esta registrado');
                    $alertas = Usuario::getAlertas();
                } else {
                    //* Si el usuario no existe guarda el registro en la BD

                    //hashear password
                    $usuario->hashearPassword();

                    // eliminar password2
                    unset($usuario->password2);

                    // Generar token
                    $usuario->crearToken();

                    // Guardar usuario nuevo
                    $resultado = $usuario->guardar();

                    // Enviar Email de confirmacion de cuenta
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                    $email->enviarConfirmacion();

                    if ($resultado) {
                        header('Location: /mensaje');
                    }
                }
            }
        }

        $router->render('auth/crear', [
            'titulo' => 'Crear cuenta',
            'usuario' => $usuario,
            'alertas' => $alertas
        ]);
    }




    //* RE4CUPERAR PASSWORD
    public static function olvide(Router $router)
    {
        $alertas = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario = new Usuario($_POST);
            $alertas = $usuario->validarEmail();

            if (empty($alertas)) {
                $usuario = Usuario::where('email', $usuario->email);

                if ($usuario && $usuario->confirmado) {
                    // Generar nuevo token
                    $usuario->crearToken();
                    unset($usuario->password2);


                    // Actualizar password
                    $usuario->guardar();


                    //Enviar email
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                    $email->enviarInstrucciones();

                    //Imprimir alerta
                    Usuario::setAlerta('exito', 'Hemos enviado las instrucciones a tu email');
                } else {
                    Usuario::setAlerta('error', 'El usuario no existe o no esta confirmado');
                }
            }
        }

        $alertas = Usuario::getAlertas();

        $router->render('auth/olvide', [
            'titulo' => 'Olvide mi password',
            'alertas' => $alertas
        ]);
    }




    //* REESTABLECER PASSWORD 
    public static function reestablecer(Router $router)
    {
        $token = s($_GET['token']);
        $error = false;

        if (!$token) header('Location: /');


        // Identificar al usuario por el token
        $usuario = Usuario::where('token', $token);
        if (empty($usuario)) {
            Usuario::setAlerta('error', 'Token no válido');
            $error = true;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // Añadir el nuevo password
            $usuario->sincronizar($_POST);


            // Validar Password
            $alertas = $usuario->validarPassword();

            if (empty($alertas)) {
                // Hashear nuevo password
                $usuario->hashearPassword();

                // Eliminar token
                $usuario->token = null;

                // Guardar nuevo password en la bd
                $resultado = $usuario->guardar();

                // redireccionar al usuario
                if ($resultado) header('Location: /');
            }
        }

        $alertas = Usuario::getAlertas();

        $router->render('auth/reestablecer', [
            'titulo' => 'Crear nuevo password',
            'error' => $error,
            'alertas' => $alertas
        ]);
    }





    //* MENSAJE DE CONFIRMACION DE CUENTA
    public static function mensaje(Router $router)
    {


        $router->render('auth/mensaje', [
            'titulo' => 'Cuenta creada'
        ]);
    }





    //* CONFIRMACION DE CUENTA
    public static function confirmar(Router $router)
    {
        $token = s($_GET['token']);
        $error = false;

        if (!$token) header('Location: /');

        // Encontrar al usuario en la BD con el token
        $usuario = Usuario::where('token', $token);

        if (empty($usuario)) {
            // Si no existe el usuario con ese token
            Usuario::setAlerta('error', 'Token no Válido');
            $error = true;
        } else {
            // Confirmar la cuenta
            $usuario->confirmado = 1;
            $usuario->token = null;
            unset($usuario->password2);


            // Guardar en la BD
            $usuario->guardar();

            // Alerta de confirmacion
            Usuario::setAlerta('exito', 'Cuenta confirmada correctamente');
        }

        $alertas = Usuario::getAlertas();
        $router->render('auth/confirmar', [
            'titulo' => 'Confirma tu cuenta',
            'error' => $error,
            'alertas' => $alertas
        ]);
    }
}
