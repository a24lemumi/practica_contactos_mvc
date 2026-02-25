<?php
    /**
     * Descripcion: Modelo de datos para la gestión de contactos. 
     * Implementa las operaciones CRUD (Create, Read, Update, Delete) y consultas específicas para la entidad Contacto.
     *
     * @author Miguel Ángel Leiva
     * @date 24-02-2026
    */
        
    namespace App\Models;

    use App\Exceptions\DatabaseException;

    class ContactoModel extends DBAbstractModel 
    {

        // ID único del contacto
        private $id;
        
        // Nombre del contacto
        private $nombre;
        
        // Teléfono del contacto
        private $telefono;
        
        // Email del contacto
        private $email;
        
        // Fecha de creación del registro
        private $created_at;  
        
        // Fecha de última actualización del registro
        private $updated_at;

        // SETTERS
        // Establece el ID del contacto
        public function setId($id) { $this->id = $id; }
        
        // Establece el nombre del contacto
        public function setNombre($nombre) { $this->nombre = $nombre; }
        
        // Establece el teléfono del contacto
        public function setTelefono($telefono) { $this->telefono = $telefono; }
        
        // Establece el email del contacto
        public function setEmail($email) { $this->email = $email; }
    
        // GETTERS
        // Obtiene el ID del contacto
        public function getId() { return $this->id; }
        
        // Obtiene el nombre del contacto
        public function getNombre() { return $this->nombre; }
        
        // Obtiene el teléfono del contacto
        public function getTelefono() { return $this->telefono; }
        
        // Obtiene el email del contacto
        public function getEmail() { return $this->email; }
        
        // Obtiene la fecha de creación
        public function getCreatedAt() { return $this->created_at; }
        
        // Obtiene la fecha de última actualización
        public function getUpdatedAt() { return $this->updated_at; }

        /* MÉTODOS CRUD */

        // Obtener un contacto por ID y cargar sus datos en el objeto
        public function get($id = '') 
        {
            try {
                $this->query = "SELECT * FROM contactos WHERE id = :id";
                $this->parametros['id'] = $id;
                $this->get_results_from_query();
                
                if (count($this->rows) === 1) {
                    $row = $this->rows[0];
                    // Sustituimos la magia por Setters explícitos para mayor claridad
                    $this->setId($row['id']);
                    $this->setNombre($row['nombre']);
                    $this->setTelefono($row['telefono']);
                    $this->setEmail($row['email']);
                    $this->created_at = $row['created_at'] ?? null;
                    $this->updated_at = $row['updated_at'] ?? null;
                    
                    $this->mensaje = 'Contacto encontrado';
                    return $row;
                } 
                
                $this->mensaje = 'Contacto no encontrado';
                return null;
                
            } catch (\Exception $e) {
                // Usamos nuestra clase de excepción para registrar el fallo
                $error = new DatabaseException("Error en BD: " . $e->getMessage());
                $error->logError();
                //Pasamos el error al servicio que lo pasa el controlador para manejarlo correctamente
                throw $error;
            }
        }

        // Insertar un nuevo contacto en la base de datos
        public function set() 
        {
            try {
                $this->query = "INSERT INTO contactos (nombre, telefono, email) VALUES (:nombre, :telefono, :email)";
                $this->parametros = [
                    'nombre' => $this->nombre,
                    'telefono' => $this->telefono,
                    'email' => $this->email
                ];
                
                $this->execute_single_query();
                $this->mensaje = 'Contacto insertado correctamente';
                return true;
                
            } catch (\Exception $e) {
                // Usamos nuestra clase de excepción para registrar el fallo
                $error = new DatabaseException("Error en BD: " . $e->getMessage());
                $error->logError();
                //Pasamos el error al servicio que lo pasa el controlador para manejarlo correctamente
                throw $error;
            }
        }
    
        // Actualizar un contacto existente en la base de datos
        public function edit() 
        {
            try {
                $this->query = "UPDATE contactos SET nombre = :nombre, telefono = :telefono, email = :email, updated_at = NOW() WHERE id = :id";
                $this->parametros = [
                    'id' => $this->id,
                    'nombre' => $this->nombre,
                    'telefono' => $this->telefono,
                    'email' => $this->email
                ];
                
                $this->execute_single_query();
                $this->mensaje = 'Contacto actualizado correctamente';
                return $this->affected_rows > 0;
                
            } catch (\Exception $e) {
                $error = new DatabaseException("Error en BD: " . $e->getMessage());
                $error->logError();
                throw $error;
            }
        }

        // Eliminar un contacto por ID de la base de datos
        public function delete($id = '') 
        {
            try {
                $this->query = "DELETE FROM contactos WHERE id = :id";
                $this->parametros = ['id' => $id];
                
                $this->execute_single_query();
                $this->mensaje = 'Contacto eliminado correctamente';
                return $this->affected_rows > 0;
                
            } catch (\Exception $e) {
                $error = new DatabaseException("Error en BD: " . $e->getMessage());
                $error->logError();
                throw $error;
            }
        }

        // Obtener todos los contactos de la base de datos
        public function getAll() 
        {
            try {
                $this->query = "SELECT * FROM contactos ORDER BY id DESC";
                $this->get_results_from_query();
                return $this->rows;
                
            } catch (\Exception $e) {
                $error = new DatabaseException("Error en BD: " . $e->getMessage());
                $error->logError();
                throw $error;
            }
        }
        
        // Obtener contactos filtrados por nombre
        public function getByFilter($filter) 
        {
            try {
                $this->query = "SELECT * FROM contactos WHERE nombre LIKE :nombre ORDER BY id DESC";
                $this->parametros['nombre'] = "%$filter%";
                $this->get_results_from_query();
                return $this->rows;
                
            } catch (\Exception $e) {
                $error = new DatabaseException("Error en BD: " . $e->getMessage());
                $error->logError();
                throw $error;
            }
        }

        // Contar el total de contactos
        public function countAll() 
        {
            try {
                $this->query = "SELECT COUNT(*) as total FROM contactos";
                $this->parametros = []; 
                $this->get_results_from_query();
                return (int) ($this->rows[0]['total'] ?? 0);
                
            } catch (\Exception $e) {
                $error = new DatabaseException("Error en BD: " . $e->getMessage());
                $error->logError();
                throw $error;
            }
        }

        // Obtener los contactos más recientes
        public function getLatest($limite) 
        {
            try {
                $this->query = "SELECT * FROM contactos ORDER BY id DESC LIMIT :limite";
                $this->parametros['limite'] = (int) $limite;
                $this->get_results_from_query();
                return $this->rows;
                
            } catch (\Exception $e) {
                $error = new DatabaseException("Error en BD: " . $e->getMessage());
                $error->logError();
                throw $error;
            }
        }
    }