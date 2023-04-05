<?php
function agregar_img_por_zona()
{
  $current_user = wp_get_current_user();
  $user_role = array_shift($current_user->roles); //"administrador"
  date_default_timezone_set('America/Argentina/Buenos_Aires');
  $fecha_actual = date('d/m/Y H:i:s');
  //DEBUG
  debug_array('xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx<br>');
  debug_array('CARGAR IMAGENES<br>');
  debug_array('El usuario que realizo la carga es: '.$user_role.' | '.$fecha_actual.'<br>');
  $args = array(
    'post_type' => 'attachment',
    'post_status' => 'any',
    'orderby' => 'ID',
    'order' => 'ASC',
    'posts_per_page' => -1
  );
  $query = new WP_Query($args);
  $attachments = array();
    if ($query->have_posts()) {
      while ($query->have_posts()) {
        $query->the_post();

        /*
        * Crea un array del estilo
        * $attachments = [
        "7630001" => 124,
        "7540003" => 123
        ] 
        */
        $post_id = get_the_ID();
        $file = get_the_title();

        //$file = get_post_meta($post_id, '_wp_attached_file', true);
        $zona_id = substr($file, 0, 7);
        $attachments[$zona_id] = $post_id;
      }
      wp_reset_postdata();
    }else{
      $attachments['noimagenes']= 'No existe Imagenes';
    }
    //DEBUG
    debug_array('Array armado de imagenes, el key es la ZONA ID y el value es el ID de la imagen<br>');
    debug_array($attachments);
    debug_array('<br>------------<br>');
  //-----------PRIMER BUCLE QUE BUSCA LAS IMAGENES------------


  $tecnicos = array();
  $args_tec = array(
    'post_type' => 'tecnicos',
    'post_status' => 'publish',
    'orderby' => 'ID',
    'order' => 'ASC',
    'posts_per_page' => -1
  );
  $query_tec = new WP_Query($args_tec);
  $count = 0;
  if ($query_tec->have_posts()) {
    while ($query_tec->have_posts()) {
      $query_tec->the_post();

      $post_id_tec = get_the_ID(); // 145
      $zona_id_tec = get_post_meta($post_id_tec, 'zona_id', true); //2322002
      //DEBUG
      debug_array('Id del tecnico: '.$post_id_tec);
      debug_array('| zona id del tecnico: '.$zona_id_tec);
      if (array_key_exists($zona_id_tec, $attachments)) {
        //DEBUG
        debug_array('se encontro la imagen del tecnico: '.$zona_id_tec.'<br>');
        debug_array('------------<br>');
        $post_id_imagen = $attachments[$zona_id_tec];
        update_post_meta($post_id_tec, 'tecnico_foto', $post_id_imagen); //se agrega un valor al meta_value
        $count++;
      }else{
        debug_array('No hay imagen del tecnico: '.$zona_id_tec.'<br>');
        debug_array('------------<br>');
      }
    }
    wp_reset_postdata();
  }
  else{
    $tecnicos['notecnicos']= 'No existen técnicos';
    debug_array('ERROR:No hay tecnicos<br>');
    debug_array('------------<br>');
  }
  $return = '<div class="updated notice is-dismissible"> <p>Imagenes Cargadas correctamente!</p> 
  <h3> Cantidad de imagenes=' . $count . '</h3>
  </div>';
  //DEBUG
  debug_array('Imagenes cargadas: '.$count);
  debug_array('xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx<br>');
  return $return;
}
?>