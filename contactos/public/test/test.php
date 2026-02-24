<?php
    /**
     * Prueba para ver el funcionamiento de la librería de depuración.
    */

    require_once __DIR__ . '/../../app/config/bootstrap.php';

    echo "<h1>Prueba de Whoops</h1>";
    echo "<p>Si ves esta página significa que el bootstrap está funcionando.</p>";
    echo "<p>Ahora vamos a generar un error:</p>";

    $undefined_variable['key'];
