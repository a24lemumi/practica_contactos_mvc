<?php
    /**
     * Descripcion: Excepción personalizada para errores relacionados con la base de datos.
     *
     * @author Miguel Ángel Leiva
     * @date 24-02-2026
    */

    namespace App\Exceptions;

    class DatabaseException extends \Exception
    {

        // Registra el error en el log del sistema
        public function logError() {
            error_log("DATABASE ERROR [" . date('Y-m-d H:i:s') . "]: " . $this->getMessage());
        }

        // Obtiene un mensaje amigable para mostrar al usuario
        public function getUserMessage() {
            return "Error de base de datos. Por favor, intente más tarde.";
        }
    }
?>