<?php

/**
 * Helpers para Vistas
 * 
 * Funciones auxiliares para ser utilizadas en las vistas de la aplicación.
 * Estas funciones proporcionan utilidades comunes para el formateo y
 * presentación de datos en las plantillas HTML.
 *
 * @author Tu Nombre
 * @version 1.0
 * @since Hito 5
 */

/**
 * Calcula y formatea los días transcurridos desde una fecha específica
 * 
 * Toma una fecha en formato string y calcula cuántos días han pasado
 * desde esa fecha hasta hoy, devolviendo un texto descriptivo amigable
 * al usuario ("Creado hoy", "Creado ayer", "Hace X días").
 *
 * @param string $fecha Fecha en formato válido para DateTime
 * @return string Texto descriptivo de los días transcurridos
 */
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