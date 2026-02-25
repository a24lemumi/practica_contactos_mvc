<?php
    /**
     * Descripción: Archivo de configuración principal de la aplicación.
     * Define constantes y configuraciones necesarias para el funcionamiento del sistema.
     * 
     * @author Miguel Ángel Leiva
     * @date 24-02-2026
    */

    require_once APP_DIR . '/config/parametros.php';

    // Configuración de base de datos desde variables de entorno
    define('DB_HOST', $_ENV['DBHOST'] ?? 'localhost');
    define('DB_NAME', $_ENV['DBNAME'] ?? 'contactos');
    define('DB_USER', $_ENV['DBUSER'] ?? 'root');
    define('DB_PASS', $_ENV['DBPASS'] ?? '');
    define('DB_PORT', $_ENV['DBPORT'] ?? 3306);
