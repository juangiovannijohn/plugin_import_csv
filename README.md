# plugin_import_csv
Plugin desarrollado para leer un .csv y que guarde en la DB de Wordpress como post los datos que estan dentro del .csv

## Primer paso
: En Ajustes/Multimedia -> Desseleccionar la casilla que dice "Organizar mis ficheros subidos en carpetas basadas por mes y año"

## Descargar los siguientes plugins gratuitos
: Advanced Custom Fields
: Custom Post Type UI
: Code Snippets

Advanced Custom Fields
: Ir a la seccion "Tools" e importar un archivo con formato .json. El archivo se encuentra dentro de éste mismo plugin en la carpeta includes/exports/ACF.tecnicos.json

Custom Post Type UI
: Ir a la seccion "Tools" y pegar el codigo en el text area de importacion. El codigo está dentro de un archivo en éste mismo plugin en la carpeta includes/exports/CPT_UI_tecnicos.json

Code Snippets
: Ir a la seccion "Imports" y subir el archivo que se encuentra en éste mismo plugin en la carpeta includes/exports/shortcode-mostrar-tabla-tecnicos.code-snippets.json

## Crear una nueva página donde mostrar la tabla
 Dentro de dicha página se debe pegar el siguiente shortcode

```
['tabla_tecnicos']
```

## Cargar Técnicos
Completado del formulario
:Se debe ingresar en el formulario el slug del CTP. Por defecto viene prerellenado con "tecnicos".
:Los meta_key por defecto vienen prerellenados con: "tecnico_dni", "tecnico_zona" y "zona_id".
:Cargar el archivo .csv, en caso de enviar otro tipo de archivo da error, y los datos pueden estar separados por ; o ,. Tener en cuenta que al pasar de un archivo Excel a CSV se debe seleccionar la opcion CSV UTF-8 (delimitado por comas).

El plugin debe mostrar un mensaje de success con la cantidad de técnicos cargados.

## Asignacion de fotos a cada Técnico
Subir imagenes
: En el menu Multimedia/Agregar Nueva subir todas las imagenes de los técnicos. 
Asignacion de imagenes a los tecnicos
: Dentro del plugin CSV Importer hacer click en el segundo boton "Cargar Imagenes". 
: El plugin muestra un mensaje de success indicando la cantidad de imagenes asignadas a tecnicos.


IMPORTANTE: El nombre del archivo debe ser la zona_id del técnico. Ejemplo: El técnico Juan Perez de zona_id= '1234567', su archivo de imagen se debe llamar 1234567.png


First Term
: This is the definition of the first term.

Second Term
: This is one definition of the second term.
: This is another definition of the second term.