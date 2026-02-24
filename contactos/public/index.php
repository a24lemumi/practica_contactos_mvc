<?php
    /**
     * Archivo de entrada principal para la aplicación de Contactos.
     * Se encarga de cargar el entorno, configurar rutas y despachar las solicitudes.
     * Autor: Miguel Ángel Leiva
     * Fecha: 24-02-2026
    */

    require_once __DIR__ . '/../app/config/bootstrap.php';

    use App\Core\Router;
    use App\Core\Dispatcher;
    use App\Controllers\IndexController;
    use App\Controllers\ContactoController;

    $router = new Router();

    // --- Definición de Rutas ---
    $router->get('/', [IndexController::class, 'indexAction']);
    $router->get('/contactos', [ContactoController::class, 'indexAction']);
    $router->get('/contactos/ver/{id}', [ContactoController::class, 'showAction']);
    $router->get('/contactos/crear', [ContactoController::class, 'createAction']);
    $router->post('/contactos/crear', [ContactoController::class, 'storeAction']);
    $router->get('/contactos/editar/{id}', [ContactoController::class, 'editAction']);
    $router->post('/contactos/editar/{id}', [ContactoController::class, 'updateAction']);
    $router->post('/contactos/eliminar/{id}', [ContactoController::class, 'deleteAction']);

    // --- Proceso de Despacho ---
    $route = $router->match($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);

    $dispatcher = new Dispatcher();
    $dispatcher->dispatch($route);