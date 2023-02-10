# plugin_import_csv
Plugin desarrollado para leer un .csv y que guarde en la DB de Wordpress como post los datos que estan dentro del .csv
### ## Primer paso
1. En Ajustes/Multimedia -> Desseleccionar la casilla que dice "Organizar mis ficheros subidos en carpetas basadas por mes y año"

### Descargar los siguientes plugins gratuitos
- [Advanced Custom Fields](https://wordpress.org/plugins/advanced-custom-fields/ "Advanced Custom Fields")
- [Custom Post Type UI](https://wordpress.org/plugins/custom-post-type-ui/ "Custom Post Type UI")
- [Code Snippets](https://wordpress.org/plugins/code-snippets/ "Code Snippets")

Advanced Custom Fields
1. Ir a la seccion "Tools" e importar un archivo con formato .json. El archivo se encuentra dentro de éste mismo plugin en la carpeta includes/exports/ACF.tecnicos.json

Custom Post Type UI
1. Ir a la seccion "Tools" y pegar el codigo en el text area de importacion. El codigo está dentro de un archivo en éste mismo plugin en la carpeta includes/exports/CPT_UI_tecnicos.json

Code Snippets
1. Ir a la seccion "Imports" y subir el archivo que se encuentra en éste mismo plugin en la carpeta includes/exports/shortcode-mostrar-tabla-tecnicos.code-snippets.json

### Crear una nueva página donde mostrar la tabla
 Dentro de dicha página se debe pegar el siguiente shortcode
```
['tabla_tecnicos']
```
### Cargar Técnicos
Completado del formulario
1. Se debe ingresar en el formulario el slug del CTP. Por defecto viene prerellenado con "tecnicos".
2. Los meta_key por defecto vienen prerellenados con: "tecnico_dni", "tecnico_zona" y "zona_id".
3. Cargar el archivo .csv, en caso de enviar otro tipo de archivo da error, y los datos pueden estar separados por ; o ,. Tener en cuenta que al pasar de un archivo Excel a CSV se debe seleccionar la opcion CSV UTF-8 (delimitado por comas).

*El plugin debe mostrar un mensaje de success con la cantidad de técnicos cargados.*

### Asignacion de fotos a cada Técnico
1. Subir imagenes
	1. En el menu Multimedia/Agregar Nueva subir todas las imagenes de los técnicos.
1. Asignacion de imagenes a los tecnicos
	1. Dentro del plugin CSV Importer hacer click en el segundo boton "Cargar Imagenes". 
	2. El plugin muestra un mensaje de success indicando la cantidad de imagenes asignadas a tecnicos.

IMPORTANTE: El nombre del archivo debe ser la zona_id del técnico. Ejemplo: El técnico Juan Perez de zona_id= '1234567', su archivo de imagen se debe llamar 1234567.pn