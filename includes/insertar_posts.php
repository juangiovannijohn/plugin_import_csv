<?php
function insertar_posts($datos_posts) {
    global $wpdb;

    try {
        
        $table_posts = $wpdb->prefix.'posts';
        $table_posts_meta = $wpdb->prefix.'postmeta';
        debug_array('Nombre de tabla POST:');
        debug_array($table_posts);
        debug_array('-----------------------------');
        debug_array('Nombre de tabla POST META:');
        debug_array($table_posts_meta);
        debug_array('-----------------------------');

        $cant_tec = count($datos_posts);
        $count = 0;
        $wpdb->query('START TRANSACTION');
        foreach ($datos_posts as $datos_post){
            $post =   $datos_post['posts'];
            //se inserta en la BD y se obtiene le ultimo id creado
            $sentencia = $wpdb->insert($table_posts, $post);
            $lastid = $wpdb->insert_id;

            //array de posts_meta
            $post_meta = $datos_post['posts_meta'];
            $string = $post_meta['tecnico_dni'];
            $tecnico_dni = substr($string, -4);
            $tecnico_dni_fin = substr_replace($tecnico_dni, ".", 1, 0);
            $meta_dni = [
                'post_id' =>  $lastid,
                'meta_key' =>  'tecnico_dni',
                'meta_value' =>  $tecnico_dni_fin 
            ];
            $meta_prov = [
                'post_id' =>  $lastid,
                'meta_key' =>  'tecnico_zona',
                'meta_value' =>  $post_meta['tecnico_zona']
            ];
            $meta_foto = [
                'post_id' =>  $lastid,
                'meta_key' =>  'tecnico_foto',
                'meta_value' =>  $post_meta['tecnico_foto']
            ];
            $meta_zona = [
                'post_id' =>  $lastid,
                'meta_key' =>  'zona_id',
                'meta_value' =>  $post_meta['zona_id']
            ];

            $wpdb->insert($table_posts_meta, $meta_dni);
            $wpdb->insert($table_posts_meta,  $meta_prov);
            $wpdb->insert($table_posts_meta, $meta_foto);
            $wpdb->insert($table_posts_meta, $meta_zona);

            if ($sentencia == 1) {
                $count++; //suma solo si se inserto correctamente
            }else{
                debug_array('Error con:'.$post['post_title']);
                debug_array('-----------------------------');
                debug_array($post);
                debug_array('-----------------------------');
                debug_array($post_meta);
                debug_array('-----------------------------');
            }
        }

        //si la cantidad de tecnicos subidas es igual a la cantidad de tecnicos guardadas en la base de datos es igual devuelve true
        
        if ($cant_tec == $count) {
            $wpdb->query('COMMIT'); 
            debug_array('SE REALIZO EL COMMIT DE LA TRANSACCION');
            debug_array('-----------------------------');
            return true;
        }else{
            $wpdb->query('ROLLBACK'); 
            debug_array('ERROR: SE REALIZO EL ROLLBACK DE LA TRANSACCION');
            debug_array('-----------------------------');
            return false;
        }

    } catch (Exception $e) {
        // Si ocurre algún error, deshace los cambios
       $wpdb->query('ROLLBACK');
       debug_array('ERROR: NO SE PUDO EJECUTAR LOS QUERYS, ENTRO EN CATCH');
       debug_array('-----------------------------');
        echo $e;
        return false; // devuelve false si hay un error
    }
}