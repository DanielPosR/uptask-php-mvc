<?php

namespace Controllers;

use Model\Proyecto;
use Model\Tarea;
use MVC\Router;
use Model\Usuario;

class DashboardController
{

    //* PROYECTOS / PRINCIPAL
    public static function index(Router $router)
    {
        session_start();
        isAuth();

        $id = $_SESSION['id'];
        $proyectos = Proyecto::belongsTo('propietarioId', $id);


        $router->render('dashboard/index', [
            'titulo' => 'Proyectos',
            'proyectos' => $proyectos
        ]);
    }



    //* CREAR PROYECTOS
    public static function crear_proyecto(Router $router)
    {
        session_start();
        isAuth();
        $alertas = [];


        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $proyecto = new Proyecto($_POST);

            // Validacion
            $alertas = $proyecto->validarProyecto();

            if (empty($alertas)) {
                // Generar URL unica
                $hash = md5(uniqid());
                $proyecto->url = $hash;

                // Almacenar creador del proyecto
                $proyecto->propietarioId = $_SESSION['id'];


                // Guardar proyecto
                $proyecto->guardar();


                // Redireccionar usuario
                header('Location: /proyecto?id=' . $proyecto->url);
            }
        }

        $router->render('dashboard/crear-proyecto', [
            'titulo' => 'Crear Proyecto',
            'alertas' => $alertas
        ]);
    }




    //* MUESTRA LA VISTA DEL PROYECTO SELECCIONADO O QUE FUE RECIEN CREADO
    public static function proyecto(Router $router)
    {
        session_start();
        isAuth();

        $token = $_GET['id'];
        if (!$token) header('Location: /dashboard');


        // Revisar que la persona que accede al proyecto es quien lo creo
        $proyecto = Proyecto::where('url', $token);

        if ($proyecto->propietarioId !== $_SESSION['id']) header('Location: /dashboard');

        $router->render('dashboard/proyecto', [
            'titulo' => $proyecto->proyecto
        ]);
    }





    //* ELIMINAR UN PROYECTO -----------------------------------------------
    public static function eliminar_proyecto()
    {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $proyecto = Proyecto::where('url', $_POST['id']);
            session_start();

            $tareas = Tarea::belongsTo('proyectoId', $_POST['id']);

            foreach ($tareas as $tarea) {
                $tarea->eliminar();
            }

            $proyecto = new Proyecto($_POST);
            $proyecto->eliminar();

            echo json_encode(['eliminado' => true]);
        }
    }



    //* PERFIL DE USUARIO
    public static function perfil(Router $router)
    {
        session_start();
        isAuth();
        $alertas = [];

        $usuario = Usuario::find($_SESSION['id']);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario->sincronizar($_POST);

            $alertas = $usuario->validar_perfil();

            if (empty($alertas)) {

                $existeUsuario = Usuario::where('email', $usuario->email);

                if ($existeUsuario && $existeUsuario->id !== $usuario->id) {
                    // Mostrar mensaje de error
                    Usuario::setAlerta('error', 'Email ya registrado en otra cuenta');
                    $alertas = $usuario->getAlertas();
                } else {
                    // Guardar nuevos datos de usuario
                    $usuario->guardar();

                    Usuario::setAlerta('exito', 'Guardado Correctamente');
                    $alertas = $usuario->getAlertas();

                    // Asigna el nombre nuevo a la barra
                    $_SESSION['nombre'] = $usuario->nombre;
                }
            }
        }

        $router->render('dashboard/perfil', [
            'titulo' => 'Perfil',
            'usuario' => $usuario,
            'alertas' => $alertas
        ]);
    }





    //* CAMBIAR PASSWORD
    public static function cambiar_password(Router $router)
    {
        session_start();
        isAuth();

        $alertas = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $usuario = Usuario::find($_SESSION['id']);

            // Sincronizar los datos del usuario
            $usuario->sincronizar($_POST);

            $alertas = $usuario->nuevoPassword();


            if (empty($alertas)) {

                $resultado = $usuario->comprobarPassword();

                if ($resultado) {
                    // Asignar el nuevo password al objeto de usuario
                    $usuario->password = $usuario->password_nuevo;


                    // Eliminar propiedades no necesarias
                    unset($usuario->password_actual);
                    unset($usuario->password_nuevo);


                    // Hashear el nuevo password
                    $usuario->hashearPassword();

                    // Almacenar el nuevo password
                    $resultado = $usuario->guardar();


                    if ($resultado) {
                        Usuario::setAlerta('exito', 'Password Guardado Correctamente');
                        $alertas = $usuario->getAlertas();
                    }
                } else {
                    Usuario::setAlerta('error', 'El password actual es incorrecto');
                    $alertas = $usuario->getAlertas();
                }
            }
        }


        $router->render('dashboard/cambiar-password', [
            'titulo' => 'Cambiar Password',
            'alertas' => $alertas
        ]);
    }
}
