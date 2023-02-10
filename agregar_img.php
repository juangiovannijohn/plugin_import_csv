<?php
function agregar_img_por_zona()
{
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
        $file = get_post_meta($post_id, '_wp_attached_file', true);
        $zona_id = substr($file, 0, 7);
        $attachments[$zona_id] = $post_id;
      }
      wp_reset_postdata();
    }else{
      $attachments['noimagenes']= 'No existe Imagenes';
    }

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
      $tecnicos[$post_id_tec] = get_the_title($post_id_tec); //Juan manuel rodriguez
      $zona_id_tec = get_post_meta($post_id_tec, 'zona_id', true); //2322002
      if (array_key_exists($zona_id_tec, $attachments)) {
        $post_id_imagen = $attachments[$zona_id_tec];
        update_post_meta($post_id_tec, 'tecnico_foto', $post_id_imagen); //se agrega un valor al meta_value
        $count++;
      }
    }
    wp_reset_postdata();
  }
  else{
    $tecnicos['notecnicos']= 'No existen técnicos';
  }
  $return = '<div class="updated notice is-dismissible"> <p>Imagenes Cargadas correctamente!</p> 
  <h3> Cantidad de imagenes=' . $count . '</h3>
  </div>';
  return $return;
}
?>