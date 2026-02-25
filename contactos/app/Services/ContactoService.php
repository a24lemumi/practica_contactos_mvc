<?php
    /**
     * Descripcion: Servicio de lógica de negocio para la gestión de contactos.
     * Centraliza todas las operaciones relacionadas con contactos.
     *
     * @author Miguel Ángel Leiva
     * @date 24-02-2026
    */

    namespace App\Services;

    use App\Models\ContactoModel;
    use App\Exceptions\DatabaseException;

    class ContactoService
    {
        // Instancia del modelo de contactos
        private ContactoModel $contactoModel;
       
        // Constructor del servicio de contactos
        public function __construct()
        {
            $this->contactoModel = new ContactoModel();
        }

        // Obtener un listado de contactos con filtros opcionales
        public function obtenerListado(array $filtros = []): array
        {
            try {
                $q = isset($filtros['q']) && trim($filtros['q']) !== '' ? trim($filtros['q']) : null;
                
                $contactos = ($q !== null) 
                    ? $this->contactoModel->getByFilter($q) 
                    : $this->contactoModel->getAll();

                return array_map(function (array $row) {
                    return $this->mapearContacto($row);
                }, $contactos);

            } catch (DatabaseException $e) { 
                error_log("Error en Service::obtenerListado: " . $e->getMessage());
                throw $e; 
            }
        }

        // Obtener los datos de un contacto específico
        public function obtenerContacto(int $id): ?array
        {
            try {
                $row = $this->contactoModel->get($id);
                if ($row === null) {
                    return null;
                }
                
                return [
                    'contacto' => $this->mapearContacto($row)
                ];
                
            } catch (DatabaseException $e) {
                error_log('Error en Service::obtenerContacto: ' . $e->getMessage());
                throw $e;
            }
        }

        // Crear un nuevo contacto
        public function crearContacto(array $datos): int
        {
            try {
                $this->contactoModel->setNombre($datos['nombre'] ?? '');
                $this->contactoModel->setTelefono($datos['telefono'] ?? null);
                $this->contactoModel->setEmail($datos['email'] ?? null);
                
                $resultado = $this->contactoModel->set();
                
                if (!$resultado) {
                    throw new \Exception('El modelo no pudo guardar el contacto.');
                }

                return (int)$this->contactoModel->getLastInsertId();

            } catch (DatabaseException $e) {
                error_log("Error en Service::crearContacto: " . $e->getMessage());
                throw $e;
            }
        }

        // Actualizar un contacto existente
        public function actualizarContacto(int $id, array $datos): bool
        {
            try {
                // Verificar que el contacto existe
                $contactoExiste = $this->contactoModel->get($id);
                if (!$contactoExiste) {
                    throw new \Exception('El contacto no existe.');
                }

                // Establecer los nuevos datos
                $this->contactoModel->setId($id);
                $this->contactoModel->setNombre($datos['nombre'] ?? '');
                $this->contactoModel->setTelefono($datos['telefono'] ?? null);
                $this->contactoModel->setEmail($datos['email'] ?? null);
                
                return $this->contactoModel->edit();

            } catch (DatabaseException $e) {
                error_log("Error en Service::actualizarContacto: " . $e->getMessage());
                throw $e;
            }
        }

        // Eliminar un contacto
        public function eliminarContacto(int $id): bool 
        {
            try {
                return $this->contactoModel->delete($id);
            } catch (DatabaseException $e) {
                error_log("Error en Service::eliminarContacto: " . $e->getMessage());
                throw $e;
            }
        }

        // Obtener el total de contactos registrados
        public function getTotalContactos(): int 
        {
            try {
                return $this->contactoModel->countAll();
            } catch (DatabaseException $e) {
                error_log("Error en Service::getTotalContactos: " . $e->getMessage());
                throw $e;
            }
        }

        // Obtener los contactos más recientes
        public function getUltimosContactos(int $limite = 3): array 
        {
            try {
                $rows = $this->contactoModel->getLatest($limite);
                return array_map(function (array $row) {
                    return $this->mapearContacto($row);
                }, $rows);
            } catch (DatabaseException $e) {
                error_log("Error en Service::getUltimosContactos: " . $e->getMessage());
                throw $e;
            }
        }

        // Mapear datos de contacto para las vistas
        private function mapearContacto(array $row): array
        {
            return [
                'id'             => isset($row['id']) ? (int)$row['id'] : null,
                'nombre'         => $row['nombre'] ?? 'Sin nombre',
                'telefono'       => $row['telefono'] ?? 'No disponible',
                'email'          => $row['email'] ?? 'No disponible',
                'created_at'     => isset($row['created_at']) ? date('d/m/Y H:i', strtotime($row['created_at'])) : 'No disponible',
                'created_at_raw' => $row['created_at'] ?? null,
                'updated_at'     => isset($row['updated_at']) ? date('d/m/Y H:i', strtotime($row['updated_at'])) : 'Sin cambios'
            ];
        }
    }