<?php
function insertar_posts($datos_posts) {
    // print_r($datos_posts[0]['posts']);
    // echo '<br>';
    // echo '<hr>';
    // echo '<br>';
    // var_dump($datos_posts[0]['posts_meta']);
    global $wpdb;










    try {
        
        $table_posts = $wpdb->prefix.'posts';
        $table_posts_meta = $wpdb->prefix.'postmeta';

        $cant_tec = count(($datos_posts));
        $count = 0;
        $wpdb->query('START TRANSACTION');
        foreach ($datos_posts as $datos_post){
            $posts =   $datos_post['posts'];
            //se inserta en la BD y se obtiene le ultimo id creado
            $sentencia = $wpdb->insert($table_posts, $posts);
            $lastid = $wpdb->insert_id;

            //array de posts_meta
            $posts_meta = $datos_post['posts_meta'];
            //$posts_meta = array_merge($posts_meta, [ 'post_id' => $lastid]);
            $meta_dni = [
                'post_id' =>  $lastid,
                'meta_key' =>  'tecnico_dni',
                'meta_value' =>  $posts_meta['tecnico_dni']
            ];
            $meta_prov = [
                'post_id' =>  $lastid,
                'meta_key' =>  'tecnico_zona',
                'meta_value' =>  $posts_meta['tecnico_zona']
            ];
            $meta_foto = [
                'post_id' =>  $lastid,
                'meta_key' =>  'tecnico_foto',
                'meta_value' =>  $posts_meta['tecnico_foto']
            ];
            $meta_zona = [
                'post_id' =>  $lastid,
                'meta_key' =>  'zona_id',
                'meta_value' =>  $posts_meta['zona_id']
            ];
            // var_dump($meta_dni);
            // echo '<br>';
            $wpdb->insert($table_posts_meta, $meta_dni);
            $wpdb->insert($table_posts_meta,  $meta_prov);
            $wpdb->insert($table_posts_meta, $meta_foto);
            $wpdb->insert($table_posts_meta, $meta_zona);

            //echo 'Valor de $sentencia: '.$sentencia;
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

return false;  
}