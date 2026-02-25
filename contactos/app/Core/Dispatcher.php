<?php
    /**
     * Descripcion: Archivo de entrada principal para la aplicación de Contactos.
     * Se encarga de cargar el entorno, configurar rutas y despachar las solicitudes.
     * 
     * @author Miguel Ángel Leiva
     * @date 24-02-2026
    */

    namespace App\Core;

    class Dispatcher 
    {
        // Ejecuta la lógica asociada a una ruta encontrada por el Router.
        public function dispatch(?array $route) 
        {
            if (!$route) {
                return $this->handleNotFound();
            }

            [$controllerName, $actionName] = $route['handler'];
            $params = $route['params'] ?? [];


            if (!class_exists($controllerName)) {
                return $this->handleError("El controlador '$controllerName' no existe.");
            }


            $controller = new $controllerName();

            if (!method_exists($controller, $actionName)) {
                return $this->handleError("La acción '$actionName' no existe en el controlador.");
            }

            return call_user_func_array([$controller, $actionName], $params);
        }

        // Se encarga de mostrar un mensaje de error específico cuando no se encuentra una ruta coincidente.
        private function handleNotFound() 
        {
            http_response_code(404);
            $errorManager = new \App\Controllers\BaseController();
            $errorManager->mostrarError("Lo sentimos, la página que buscas no existe.", 404);
        }

        // Se encarga de mostrar un mensaje de error genérico cuando ocurre un problema con el controlador o la acción.
        private function handleError(string $mensaje) 
        {
            http_response_code(500);
            $errorManager = new \App\Controllers\BaseController();
            $errorManager->renderHTML(VIEWS_DIR . '/errors/general_error.php', ['mensaje' => $mensaje]);
        }
    }