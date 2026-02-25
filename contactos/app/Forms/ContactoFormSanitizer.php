<?php
    /**
     * Descripcion: Clase encargada de sanitizar los datos del formulario de contacto.
     * 
     * @author Miguel Ángel Leiva
     * @date 24-02-2026
    */

    namespace App\Forms;

    // Clase para sanitizar los datos del formulario de contacto
    class ContactoFormSanitizer
    {
        // Sanitiza un array completo de datos
        public function sanitize(array $data): array
        {
            $sanitized = [];
            foreach ($data as $key => $value) {
                $sanitized[$key] = $this->sanitizeField($key, $value);
            }
            return $sanitized;
        }

        // Sanitiza datos para mostrar de forma segura en HTML
        public function sanitizeForOutput(array $data): array
        {
            return array_map(function($value) {
                return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
            }, $data);
        }

        // Sanitiza un campo específico según su tipo
        private function sanitizeField(string $field, $value)
        {
            if (!is_string($value)) return $value;
            $value = trim($value);

            return match ($field) {
                'nombre'   => mb_convert_case(preg_replace('/[^\p{L}\s\-\.\']/u', '', $value), MB_CASE_TITLE, "UTF-8"),
                'telefono' => preg_replace('/[^\d+]/', '', $value),
                'email'    => filter_var(mb_strtolower($value), FILTER_SANITIZE_EMAIL),
                default    => $value,
            };
        }
    }