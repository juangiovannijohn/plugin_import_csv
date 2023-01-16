<?php
/*
Plugin Name: Import CSV
Plugin URI: https://githun.com/import-csv
Description: Este plugin permite importar un archivo CSV y crear nuevos post types.
Version: 1.1.0
Author: Juan Rodriguez
Author URI: https://iCornio.com
License: GPL2
*/
include('./includes/example_CSV.png');

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
    *{ 
    margin: 0px;
    padding: 0px;
    box-sizing: border-box;
}
.container{
    width: 100%;
    padding: 1rem;
    display: flex;
    flex-direction: row;
    flex-wrap: nowrap;
}
.col{
    width: 100%;
    height: 100%;
}
.col_img{
    display: flex;
    flex-direction: column;
    flex-wrap: nowrap;
}
.col_img img{
    padding: 2rem;
    width: 400px;
    height: auto;
    border-radius: 10px;
}
</style>
<div style="min-height: 100vh;" class="wrap">
<h1>CSV Importer</h1>
<div> Plugin desarrollado por <a href="https://icornio.com" target="_blank">iCornio Tech</a> </div>
    <hr>
    <div class="container">
        <div class="col col_form">
            <h3>Formulario</h3>
            <form method="post" enctype="multipart/form-data">
            <label for="cpt">Ingrese el slug del Custom Post type a cargar</label></br>
            <input id="cpt" name="cpt_slug" type="text" value="tecnicos" > SISCARD, Si quiere cargar técnicos debe colocar: tecnicos</br>
            <label for="cf_1">Ingrese el meta_key del primer Custom Field</label></br>
            <input id="cf_1" name="cf_1" type="text" value="tecnico_dni"> SISCARD, Si quiere cargar técnicos debe colocar: tecnico_dni</br>
            <label for="cf_2">Ingrese el meta_key del segundo Custom Field</label></br>
            <input id="cf_2" name="cf_2" type="text" value="tecnico_zona"> SISCARD, Si quiere cargar técnicos debe colocar: tecnico_zona</br>
            <label for="file">Subir un Archivo .csv</label></br>
            <input type="file" id="file" name="file"></br>
            </br>
            <input type="submit" name="submit" value="Cargar">
            </form>
        </div>
        <div class="col col_img">
            <h3>Ejemplo del archivo</h3>
            <img src="<?php echo plugin_dir_url( __FILE__ ) . 'includes/example_CSV.png' ?>" alt="ejemplo">
        </div>
    </div>
</div>
<?php
    if (isset($_POST['submit'])) {
        // Get the post type from the form
        $post_type = $_POST['cpt_slug'];

        // Delete all posts with the specified post type
        $args = array(
            'post_type' => $post_type,
            'post_status' => 'any',
            'numberposts' => -1
        );
        $posts = get_posts( $args );
        foreach ( $posts as $post ) {
            wp_delete_post( $post->ID, true );
        }

        // Rest of the code for handling the CSV file and creating new posts
        $file = $_FILES['file'];
        $file_name = $file['name'];
        $file_tmp = $file['tmp_name'];
        $file_size = $file['size'];
        $file_error = $file['error'];
        $file_ext = explode('.', $file_name);
        $file_ext = strtolower(end($file_ext));
        $custom_post_type = $_POST["cpt_slug"];
        $meta_key_1 = $_POST["cf_1"];
        $meta_key_2 = $_POST["cf_2"];


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

                        $row = 1;
                        $count = 0;

                        while (($data = fgetcsv($handle, 1000, $delimiter)) !== false) {
                            if ($row > 1) {
                                //CPT debe ser exacto a lo grabado en la BD
                                $CPT_nombre = $data[0];
                                //DNI recortado para los ultimos 4 digitos
                                $string = $data[1];
                                $string = trim($string);
                                $CF_1 = substr($string, -4);
                                //Zona, string sin limites
                                $CF_2 = $data[2];

                                $post = array(
                                    'post_title'    => $CPT_nombre,
                                    'post_type'     => $custom_post_type,
                                    'post_status'   => 'publish'
                                );

                                $post_id = wp_insert_post($post);
                                if ($post_id) {
                                    add_post_meta($post_id, $meta_key_1, $CF_1);
                                    add_post_meta($post_id, $meta_key_2, $CF_2);
                                    $count++;
                                }

                            }
                            $row++;
                        }
                        fclose($handle);

                        echo '<div class="updated notice is-dismissible"> <p>Archivo importado correctamente!</p> 
                        <h3> Filas afectadas=' . $count . '</h3>
                        </div>';
                    } else {
                        echo '<div class="error notice is-dismissible"> <p>Juan: An error occurred while processing the CSV file.</p> </div>';
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
