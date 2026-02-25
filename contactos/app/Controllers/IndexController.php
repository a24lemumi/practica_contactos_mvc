<?php
    /**
     * Descripcion: Controlador de la página de inicio de la aplicación.
     * Gestiona la pantalla principal mostrando estadísticas y contactos recientes.
     *
     * @author Miguel Ángel Leiva
     * @date 24-02-2026
    */

    namespace App\Controllers;

    use App\Services\ContactoService;
    use App\Exceptions\DataBaseException;

    class IndexController extends BaseController
    {
        private ContactoService $contactoService;

        // Constructor del IndexController
        public function __construct()
        {
            parent::__construct();
            $this->contactoService = new ContactoService();
        }

        // Acción principal del controlador de inicio
        public function indexAction(): void
        {
            try {
                // Obtenemos el total de contactos registrados en el sistema
                $totalContactos = $this->contactoService->getTotalContactos();
            
                $contactosRecientes = $this->contactoService->getUltimosContactos(RECENT_CONTACTS_LIMIT);
        
                $this->renderHTML(VIEWS_DIR . '/index/index_view.php', [
                    'titulo'  => 'Inicio | Agenda Pro',
                    'total'   => $totalContactos,
                    'ultimos' => $contactosRecientes
                ]);
        
            } catch (DatabaseException $e) {
            
                $this->mostrarError($e->getMessage());

            } catch (\Exception $e) {

                $this->mostrarError("No se pudo cargar el panel de control: " . $e->getMessage(), 500);
            }
        }
    }