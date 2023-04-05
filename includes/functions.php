<?php
function debug_array($array){
    $dir = plugin_dir_path(__FILE__) . 'logs/log.txt';
    $array_string = json_encode($array);
    // Abre el archivo en modo append (para agregar información a la última línea)
    $archivo = fopen($dir, 'a');

    // Escribe la información del array en una línea del archivo
    fwrite($archivo, $array_string . "\n");

    // Cierra el archivo
    fclose($archivo);
}

function descargar_archivo() {
    // Habilitar el buffer de salida
    ob_start();
    // Ruta y nombre del archivo
    $dir = plugin_dir_url(__FILE__) . 'logs/log.txt';
    // Vaciar el buffer de salida
    ob_clean();
    // Leer y descargar el archivo
    readfile($dir);
}

