<?php

require_once __DIR__ . '/../includes/app.php';

use Controllers\DashboardController;
use Controllers\LoginController;
use Controllers\TareaController;
use MVC\Router;

$router = new Router();


//* LOGIN
$router->get('/', [LoginController::class, 'login']);
$router->post('/', [LoginController::class, 'login']);

//* CERRAR SESION
$router->get('/logout', [LoginController::class, 'logout']);


//* CREAR CUENTA
$router->get('/crear', [LoginController::class, 'crear']);
$router->post('/crear', [LoginController::class, 'crear']);


//* FORMULARIO OLVIDE PASSWORD
$router->get('/olvide', [LoginController::class, 'olvide']);
$router->post('/olvide', [LoginController::class, 'olvide']);


//* REESTABLECER PASSWORD
$router->get('/reestablecer', [LoginController::class, 'reestablecer']);
$router->post('/reestablecer', [LoginController::class, 'reestablecer']);



//* CONFIRMACION DE CUENTA
$router->get('/mensaje', [LoginController::class, 'mensaje']);
$router->get('/confirmar', [LoginController::class, 'confirmar']);



//* DASHBOARD DE USUARIOS / ZONA DE PROYECTOS
$router->get('/dashboard', [DashboardController::class, 'index']);

$router->post('/eliminar-proyecto', [DashboardController::class, 'eliminar_proyecto']);

$router->get('/crear-proyecto', [DashboardController::class, 'crear_proyecto']);
$router->post('/crear-proyecto', [DashboardController::class, 'crear_proyecto']);

$router->get('/proyecto', [DashboardController::class, 'proyecto']);

$router->get('/perfil', [DashboardController::class, 'perfil']);
$router->post('/perfil', [DashboardController::class, 'perfil']);


$router->get('/cambiar-password', [DashboardController::class, 'cambiar_password']);
$router->post('/cambiar-password', [DashboardController::class, 'cambiar_password']);



//* API PARA LAS TAREAS
$router->get('/api/tareas', [TareaController::class, 'index']);
$router->post('/api/tarea', [TareaController::class, 'crear']);
$router->post('/api/tarea/actualizar', [TareaController::class, 'actualizar']);
$router->post('/api/tarea/eliminar', [TareaController::class, 'eliminar']);




//* Comprueba y valida las rutas, que existan y les asigna las funciones del Controlador
$router->comprobarRutas();
