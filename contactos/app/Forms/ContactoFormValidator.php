<?php
    /**
     * Descripcion: Clase encargada de validar los datos del formulario de contacto.
     * 
     * @author Miguel Ángel Leiva
     * @date 24-02-2026
    */

    namespace App\Forms;

    // Clase para validar los datos del formulario de contacto
    class ContactoFormValidator
    {
        // Valida un array de datos y devuelve los errores encontrados
        public function validate(array $data): array
        {
            $errors = [];
            
            // Validación del nombre
            if (empty($data['nombre']) || mb_strlen($data['nombre']) < 2) {
                $errors['nombre'] = 'El nombre es obligatorio (mín. 2 caracteres)';
            }

            // Validación del correo electrónico
            if (empty($data['email'])) {
                $errors['email'] = 'El email es obligatorio';
            } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = 'El formato del email no es válido';
            }

            // Validación del teléfono
            $soloNumeros = preg_replace('/[^\d]/', '', $data['telefono'] ?? '');
            if (strlen($soloNumeros) < 9) {
                $errors['telefono'] = 'El teléfono debe tener al menos 9 dígitos';
            }

            return $errors;
        }
    }