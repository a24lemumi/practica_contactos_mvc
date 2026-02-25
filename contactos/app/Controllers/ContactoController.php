<?php
    /**
     * Descripcion: Controlador responsable de la gestión completa de contactos.
     * Maneja todas las operaciones CRUD: listar, mostrar, crear, editar y eliminar contactos.
     *
     * @author Miguel Ángel Leiva
     * @date 24-02-2026
    */

    namespace App\Controllers;

    use App\Services\ContactoService;
    use App\Forms\ContactoForm;
    use App\Exceptions\DatabaseException;

    class ContactoController extends BaseController 
    {
        private ContactoService $contactoService;
        private ContactoForm $contactoForm;
        
        // Constructor del ContactoController
        public function __construct() 
        {
            parent::__construct();
            $this->contactoService = new ContactoService();
            $this->contactoForm    = new ContactoForm();
        }

        // Acción para mostrar el listado de contactos
        public function indexAction(): void
        {
            $filtros = [
                'q' => $_GET['q'] ?? null,
            ];
        
            try {
                $contactos = $this->contactoService->obtenerListado($filtros);
                $this->renderHTML(VIEWS_DIR . '/contactos/listar_view.php', [
                    'titulo'    => 'Listado de Contactos',
                    'contactos' => $contactos,
                    'filtros'   => $filtros,
                ]);
            } catch (\App\Exceptions\DatabaseException $e) {
                $this->mostrarError($e->getMessage());
            }
        }

        // Acción para mostrar los detalles de un contacto específico
        public function showAction(int $id): void
        {
            try {
                $detalle = $this->contactoService->obtenerContacto($id);
                
                if (!$detalle) {
                    $this->mostrarError("El contacto solicitado no existe.",404);
                    return;
                }
            
                $this->renderHTML(VIEWS_DIR . '/contactos/ver_view.php', [
                    'titulo'   => "Ficha de Contacto",
                    'contacto' => $detalle['contacto']
                ]);
            } catch (\App\Exceptions\DatabaseException $e) {
                $this->mostrarError($e->getMessage());
            }
        }

        // Acción para mostrar el formulario de creación de un nuevo contacto
        public function createAction(): void
        {
            $form = $this->contactoForm->getDefaultData();
            $form = $this->contactoForm->sanitizeForOutput($form);
    
            $this->renderHTML(VIEWS_DIR . '/contactos/agregar_view.php', [
                'titulo' => 'Agregar nuevo contacto',
                'form'   => $form
            ]);
        }

        // Acción para procesar la creación de un nuevo contacto
        public function storeAction(): void
        {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                $this->redirect('/contactos');
                return;
            }

            $datos = $_POST;
            $validacion = $this->contactoForm->validate($datos);

            if (!$validacion['is_valid']) {
                $this->renderHTML(VIEWS_DIR . '/contactos/agregar_view.php', [
                    'titulo' => 'Corregir datos del contacto',
                    'form'   => $this->contactoForm->sanitizeForOutput($validacion['form']),
                    'errors' => $this->contactoForm->sanitizeForOutput($validacion['errors'])
                ]);
                return;
            }

            try {
                $this->contactoService->crearContacto($validacion['data']);
                $this->redirect('/contactos?success=created');
            } catch (\App\Exceptions\DatabaseException $e) {
                $this->renderHTML(VIEWS_DIR . '/contactos/agregar_view.php', [
                    'titulo'        => 'Error de persistencia',
                    'form'          => $this->contactoForm->sanitizeForOutput($validacion['form']),
                    'general_error' => 'No se pudo guardar el contacto. Intente de nuevo más tarde.'
                ]);
            } catch (\Exception $e) {
                $this->mostrarError("Ocurrió un error crítico: " . $e->getMessage(), 500);
            }
        }

        // Acción para mostrar el formulario de edición de contacto
        public function editAction(int $id): void
        {
            try {
                $detalle = $this->contactoService->obtenerContacto($id);
                
                if (!$detalle) {
                    $this->mostrarError("El contacto solicitado no existe.", 404);
                    return;
                }

                $form = $this->contactoForm->sanitizeForOutput($detalle['contacto']);

                $this->renderHTML(VIEWS_DIR . '/contactos/editar_view.php', [
                    'titulo'   => 'Editar contacto',
                    'form'     => $form,
                    'contacto' => $detalle['contacto']
                ]);

            } catch (\App\Exceptions\DatabaseException $e) {
                $this->mostrarError($e->getMessage());
            }
        }

        // Acción para procesar la actualización de un contacto existente
        public function updateAction(int $id): void
        {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                $this->redirect('/contactos');
                return;
            }

            try {
                $contacto = $this->contactoService->obtenerContacto($id);
                
                if (!$contacto) {
                    $this->mostrarError("El contacto solicitado no existe.", 404);
                    return;
                }

                $datos = $_POST;
                $validacion = $this->contactoForm->validate($datos);

                if (!$validacion['is_valid']) {
                    $this->renderHTML(VIEWS_DIR . '/contactos/editar_view.php', [
                        'titulo'   => 'Corregir datos del contacto',
                        'form'     => $this->contactoForm->sanitizeForOutput($validacion['form']),
                        'errors'   => $this->contactoForm->sanitizeForOutput($validacion['errors']),
                        'contacto' => $contacto['contacto']
                    ]);
                    return;
                }

                $this->contactoService->actualizarContacto($id, $validacion['data']);
                $this->redirect('/contactos?success=updated');

            } catch (\App\Exceptions\DatabaseException $e) {
                $this->renderHTML(VIEWS_DIR . '/contactos/editar_view.php', [
                    'titulo'        => 'Error de persistencia',
                    'form'          => $this->contactoForm->sanitizeForOutput($_POST),
                    'general_error' => 'No se pudo actualizar el contacto. Intente de nuevo más tarde.',
                    'contacto'      => ['id' => $id]
                ]);
            } catch (\Exception $e) {
                $this->mostrarError("Ocurrió un error crítico: " . $e->getMessage(), 500);
            }
        }

        // Acción para eliminar un contacto
        public function deleteAction(int $id): void
        {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                $this->redirect('/contactos');
                return;
            }

            try {
                $contacto = $this->contactoService->obtenerContacto($id);
                
                if (!$contacto) {
                    $this->mostrarError("El contacto solicitado no existe.", 404);
                    return;
                }

                $this->contactoService->eliminarContacto($id);
                $this->redirect('/contactos?success=deleted');

            } catch (\App\Exceptions\DatabaseException $e) {
                $this->redirect('/contactos?error=delete_failed');
            } catch (\Exception $e) {
                $this->mostrarError("Ocurrió un error crítico: " . $e->getMessage(), 500);
            }
        }
    }