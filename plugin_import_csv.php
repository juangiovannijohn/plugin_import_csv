<?php
/*
Plugin Name: Import CSV
Plugin URI: https://githun.com/import-csv
Description: Este plugin permite importar un archivo CSV y crear nuevos post types.
Version: 1.2.0
Author: iCornio Tech (Juan Giovanni John)
Author URI: https://iCornio.com
License: GPL2
*/
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}
include_once('agregar_img.php');
include_once('includes/insertar_posts.php');

// Create a top-level menu item in the dashboard
function csv_importer_menu()
{
    add_menu_page(
        'CSV Importer',
        'CSV Importer',
        'manage_options',
        'csv-importer',
        'csv_importer_page',
        'dashicons-upload'
    );
}
add_action('admin_menu', 'csv_importer_menu');

// Display the page for the plugin
function csv_importer_page()
{ ?>
    <style>
        * {
            margin: 0px;
            padding: 0px;
            box-sizing: border-box;
        }

        .container {
            width: 100%;
            padding: 1rem;
            display: flex;
            flex-direction: row;
            flex-wrap: nowrap;
        }

        .col {
            width: 100%;
            height: 100%;
        }

        .col_img {
            display: flex;
            flex-direction: column;
            flex-wrap: nowrap;
        }

        .col_img {
            padding: 0px 2rem;
            width: 100%;
        }

        .img_example {
            width: 100%;
            max-width: 600px;
            height: auto;
            border-radius: 5px;
            box-shadow: 0px 0px 10px -6px rgb(0 0 0 / 80%)
        }
    </style>
    <div style="min-height: 100vh;" class="wrap">
        <h1>CSV Importer</h1>
        <br>
        <div> Plugin desarrollado por <a href="https://icornio.com" target="_blank">iCornio Tech</a> </div>
        <hr style="margin: 10px 0px;">
        <div class="container">
            <div class="col col_form">
                <h3>1° Subir archivo y cargar técnicos</h3>
                <form method="post" enctype="multipart/form-data">
                    <label for="cpt">Ingrese el slug del Custom Post type a cargar</label></br>
                    <input class="regular-text" id="cpt" name="cpt_slug" type="text" value="tecnicos"> SISCARD, Si quiere
                    cargar técnicos debe colocar: tecnicos</br>
                    <label for="cf_1">Ingrese el meta_key del primer Custom Field</label></br>
                    <input class="regular-text" id="cf_1" name="cf_1" type="text" value="tecnico_dni"> SISCARD, Si quiere
                    cargar técnicos debe colocar: tecnico_dni</br>
                    <label for="cf_2">Ingrese el meta_key del segundo Custom Field</label></br>
                    <input class="regular-text" id="cf_2" name="cf_2" type="text" value="tecnico_zona"> SISCARD, Si quiere
                    cargar técnicos debe colocar: tecnico_zona</br>
                    <label for="cf_3">Ingrese el meta_key del segundo Custom Field</label></br>
                    <input class="regular-text" id="cf_3" name="cf_3" type="text" value="zona_id"> SISCARD, Si quiere cargar
                    técnicos debe colocar: zona_id</br>
                    <div class="wp-upload-form">
                        <label for="file">Subir un Archivo .csv</label></br>
                        <input type="file" id="file" name="file"></br>
                    </div>
                    </br>
                    <input class="button button-primary" type="submit" name="submit" value="Cargar técnicos">
                </form>
                <hr style="margin: 10px 0px;">
                <div>
                    <h3>2° Asignar foto a cada técnico</h3>
                    <form method="post">
                        <input type="hidden">
                        <button class="button button-primary" type="submit" name="ejecutar">Cargar imágenes</button>
                    </form>
                    <div>
                        <?php
                        if (isset($_POST['ejecutar'])) {
                            echo agregar_img_por_zona();
                        } ?>
                    </div>
                </div>
            </div>
            <div class="col col_img">
                <h3>Ejemplo del archivo</h3>
                <img class="img_example" src="<?php echo plugin_dir_url(__FILE__) . 'includes/example_CSV.png' ?>"
                    alt="ejemplo">
            </div>
        </div>
    </div>
    <?php
    if (isset($_POST['submit'])) {
        // Obtener el tiempo actual en segundos y microsegundos antes de llamar a la función
        $tiempo_inicio = microtime(true);
        // Get the post type from the form
        $post_type = $_POST['cpt_slug'];
        // Rest of the code for handling the CSV file and creating new posts
        $file = $_FILES['file'];
        $file_name = $file['name'];
        $file_tmp = $file['tmp_name'];
        $file_size = $file['size'];
        $file_error = $file['error'];
        $file_ext = explode('.', $file_name);
        $file_ext = strtolower(end($file_ext));
        $custom_post_type = $_POST["cpt_slug"];
        $meta_key_1 = $_POST["cf_1"]; //tecnico_dni
        $meta_key_2 = $_POST["cf_2"]; //tecnico_zona
        $meta_key_3 = $_POST["cf_3"]; //zona_id
        
        global $wpdb;
        // Delete all posts with the specified post type and custom fields
        $wpdb->query('DELETE FROM `wp_posts` WHERE post_type ="'.$post_type.'"');
        $wpdb->query('DELETE FROM `wp_postmeta` WHERE meta_key = '.$meta_key_3.' OR meta_key = '.$meta_key_1.' OR meta_key = '.$meta_key_2.' OR meta_key = "tecnico_foto"');

        //id del usuario logueado
        $user_ID = get_current_user_id();
        //Creo una variable donde agregarle los post
        $datos_posts = array();




        $allowed = array('csv');

        if (in_array($file_ext, $allowed)) {
            if ($file_error === 0) {

                $file_name_new = uniqid('', true) . '.' . $file_ext;
                $upload_dir = wp_upload_dir();
                $file_destination = $upload_dir['basedir'] . '/plugin_import_csv/' . $file_name_new;
                if (!is_dir($upload_dir['basedir'] . '/plugin_import_csv/')) {
                    wp_mkdir_p($upload_dir['basedir'] . '/plugin_import_csv/');
                }

                if (move_uploaded_file($file_tmp, $file_destination)) {
                    $delimiter = ';';
                    if (($handle = fopen($file_destination, "r")) !== false) {
                        $firstLine = fgets($handle);
                        $semicolonCount = substr_count($firstLine, ';');
                        $commaCount = substr_count($firstLine, ',');
                        if ($commaCount > $semicolonCount) {
                            $delimiter = ',';
                        }
                        rewind($handle); //establecer el puntero del archivo de nuevo al principio del archivo
                        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                            $datos_csv[] = $data;
                        }

                        fclose($handle);

                        // crear un nuevo array con los datos de los posts
                        $datos_posts = array();
                        $count = 0;
                        for ($i = 1; $i < count($datos_csv); $i++) {
                            $datos_string = $datos_csv[$i];

                            if (!empty($datos_string)) {
                                $datos = explode($delimiter, $datos_string[0]);

                                $datos_posts[] = [
                                    'posts' => [
                                        'post_author' => $user_ID,
                                        'comment_status' => 'closed',
                                        'ping_status' => 'closed',
                                        'post_title' => $datos[1],
                                        'post_name' => $datos[0],
                                        'post_type' => $custom_post_type,
                                        'post_status' => 'publish',
                                        ],
                                    'posts_meta' => [
                                        'tecnico_dni' => $datos[2],
                                        'tecnico_zona' => $datos[3],
                                        'tecnico_foto' => '',
                                        'zona_id' =>   $datos[0],
                                        ],
                                ];

                                $count = $i;
                            }
                        }

                        $result = insertar_posts($datos_posts);
                        
                        //Calculo de tiempo de ejecucion 
                        $tiempo_fin = microtime(true);
                        $tiempo_ejecucion = $tiempo_fin - $tiempo_inicio;
                        if ($result) {

                            echo '<div class="updated notice is-dismissible"> <p>Archivo importado correctamente! Demora: '.$tiempo_ejecucion.'seg</p> 
                            <h3> Filas afectadas=' . $count . '</h3>
                            </div>';
                        } else {

                            echo '<div class="error notice is-dismissible"> <p>No se pudieron actualizar correctamente todos los tecnicos, vuelva a intentarlo. Demora: '.$tiempo_ejecucion.'seg</p> </div>';
                        }


                    } else {
                        echo '<div class="error notice is-dismissible"> <p>Ha ocurrido un error al procesar el archivo .csv.</p> </div>';
                    }
                } else {
                    echo '<div class="error notice is-dismissible"> <p>No se guarda el archivo.</p> </div>';
                }
            }
        } else {
            echo '<div class="error notice is-dismissible"> <p>Solo se permiten archivos con formato .csv</p> </div>';
        }
    }
}