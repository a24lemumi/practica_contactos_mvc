<?php
    /**
     * Descripcion: Formulario de validación y sanitización para la gestión de contactos.
     * 
     * @author Miguel Ángel Leiva
     * @date 24-02-2026
    */

    namespace App\Forms;

    // Clase principal del formulario de contactos
    class ContactoForm
    {
        // Array con los datos del formulario
        private array $data = [];
        
        // Array con los errores de validación
        private array $errors = [];
        
        // Instancia del sanitizador de datos
        private ContactoFormSanitizer $sanitizer;
        
        // Instancia del validador de datos
        private ContactoFormValidator $validator;

        // Constructor que inicializa el formulario con datos opcionales
        public function __construct(array $data = [])
        {
            $this->sanitizer = new ContactoFormSanitizer();
            $this->validator = new ContactoFormValidator();
    
            $this->data = !empty($data) ? $this->sanitizer->sanitize($data) : $this->getDefaultData();
        }

        // Valida los datos del formulario y devuelve el resultado
        public function validate(array $data): array
        {
            $this->data = $this->sanitizer->sanitize($data);
            $this->errors = $this->validator->validate($this->data);

            return [
                'is_valid' => empty($this->errors),
                'errors'   => $this->errors,
                'data'     => $this->getValidatedData(), 
                'form'     => $this->getFormData()      
            ];
        }

        // Sanitiza datos para mostrar en vistas HTML
        public function sanitizeForOutput(array $data): array
        {
            return $this->sanitizer->sanitizeForOutput($data);
        }

        // Obtiene solo los datos validados necesarios
        public function getValidatedData(): array
        {
            return array_intersect_key($this->data, array_flip(['nombre', 'telefono', 'email']));
        }

        // Obtiene los datos del formulario mezclados con valores por defecto
        public function getFormData(): array
        {
            return array_merge($this->getDefaultData(), $this->data);
        }

        // Obtiene los datos por defecto del formulario
        public function getDefaultData(): array
        {
            return ['nombre' => '', 'telefono' => '', 'email' => ''];
        }
    }