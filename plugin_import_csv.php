<?php
/*
Plugin Name: Import CSV
Plugin URI: https://githun.com/import-csv
Description: Este plugin permite importar un archivo CSV y crear nuevos post types
Version: 1.0
Author: Juan Rodriguez
Author URI: https://tuweb.com
License: GPL2
*/

// Create a top-level menu item in the dashboard
function csv_importer_menu() {
    add_menu_page(
        'CSV Importer',
        'CSV Importer',
        'manage_options',
        'csv-importer',
        'csv_importer_page',
        'dashicons-upload'
    );
}
add_action( 'admin_menu', 'csv_importer_menu' );

// Display the page for the plugin
function csv_importer_page() {
    echo '<div style="min-height: 100vh; position: relative;" class="wrap">';
    echo '<h1>CSV Importer</h1>';
    echo '<div style=""> Plugin desarrollado por <a href="https://icornio.com" target="_blank">iCornio Tech</a> </div>';
    echo '<hr>';
    echo '<h2>Subir un archivo .csv</h2>';
    echo '<form method="post" enctype="multipart/form-data">';
    echo '<input type="file" name="file">';
    echo '<input type="submit" name="submit" value="Import">';
    echo '</form>';

    echo '</div>';

    if(isset($_POST['submit'])){
        $file = $_FILES['file'];
        $file_name = $file['name'];
        $file_tmp = $file['tmp_name'];
        $file_size = $file['size'];
        $file_error = $file['error'];
        $file_ext = explode('.', $file_name);
        $file_ext = strtolower(end($file_ext));

        $allowed = array('csv');

        if(in_array($file_ext, $allowed)){
            if($file_error === 0){

                    $file_name_new = uniqid('', true) . '.' . $file_ext;
                    $upload_dir = wp_upload_dir();
                    $file_destination = $upload_dir['basedir'].'/plugin_import_csv/'.$file_name_new;
                    if (!is_dir($upload_dir['basedir'].'/plugin_import_csv/')) {
                        wp_mkdir_p($upload_dir['basedir'].'/plugin_import_csv/');
                    }

                if(move_uploaded_file($file_tmp, $file_destination)){
                    $delimiter = ';';
                    if( ($handle = fopen($file_destination, "r")) !== false){
                        $firstLine = fgets($handle);
                        $semicolonCount = substr_count($firstLine, ';');
                        $commaCount = substr_count($firstLine, ',');
                        if ($commaCount > $semicolonCount) {
                            $delimiter = ',';
                        }
                        rewind($handle); //establecer el puntero del archivo de nuevo al principio del archivo

                        $row = 1;
                        $count = 0;
                        
                            while (($data = fgetcsv($handle, 1000, $delimiter)) !== false) {
                                if ($row > 1) {
                                    $proyecto_title = $data[0];
                                    $proyecto_dni = $data[1];
                                    
                                    $post = array(
                                        'post_title'    => $proyecto_title,
                                        'post_type'     => 'proyectos',
                                        'post_status'   => 'publish'
                                    );
                        
                                    $post_id = wp_insert_post($post);
                                    if($post_id) {
                                        add_post_meta($post_id, 'proyecto_dni', $proyecto_dni);
                                        $count++;
                                    }

                                }
                                $row++;
                            }
                            fclose($handle);
                        
                        echo '<div class="updated notice is-dismissible"> <p>Archivo importado correctamente!</p> 
                        <h3> Filas afectadas='. $count .'</h3>
                        </div>';
                    }else{
                        echo '<div class="error notice is-dismissible"> <p>Juan: An error occurred while processing the CSV file.</p> </div>';
                    }
                }else{
                    echo '<div class="error notice is-dismissible"> <p>No se guarda el archivo.</p> </div>'; 
                }
            }
        } else {
            echo '<div class="error notice is-dismissible"> <p>Solo se permiten archivos con formato .csv</p> </div>';
        }
        }
        }