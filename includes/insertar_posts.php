<?php
function insertar_posts($datos_posts, $meta_posts) {
    //var_dump($datos_posts);
    global $wpdb;


    try {
        $table = $wpdb->prefix.'post';
        $cant_tec = count(($datos_posts));
        $count = 0;
        $wpdb->query('START TRANSACTION');
        foreach ($datos_posts as $datos_post){
            $sentencia = $wpdb->insert($table, $datos_post);
            if ($sentencia == 1) {
                $count++; //suma solo si se inserto correctamente
            }
        }
        $wpdb->query('COMMIT');
        if ($cant_tec == $count) {
            
            return true;
        }else{
            return false;
        }

    } catch (Exception $e) {
        // Si ocurre algún error, deshace los cambios
       $wpdb->query('ROLLBACK');
       echo 'ROLLBACK';
        echo $e;
        return false; // devuelve false si hay un error
    }
    
}