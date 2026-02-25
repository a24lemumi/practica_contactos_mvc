<?php
    /**
     * Descripcion: Funciones auxiliares para ser utilizadas en las vistas de la aplicación.
     *
     * @author Miguel Ángel Leiva
     * @date 24-02-2026
    */

    // Calcula y formatea los días transcurridos desde una fecha específica
    function diasTranscurridos(string $fecha): string 
    {
        $fechaInicio = new DateTime($fecha);
        $hoy = new DateTime();
        $diferencia = $hoy->diff($fechaInicio);
        
        $dias = $diferencia->days;

        if ($dias === 0) return "Creado hoy";
        if ($dias === 1) return "Creado ayer";
        return "Hace $dias días";
    }