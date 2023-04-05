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
