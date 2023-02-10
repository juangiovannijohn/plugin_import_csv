# CSV Importer - Carga automática de técnicos

Plugin desarrollado para leer un .csv y que guarde en la DB de Wordpress como post los datos que estan dentro del .csv

![](https://siscard.com/wp-content/uploads/csv_importer_dashboard.png)

> Dashboard.

**Tabla de Contenido**
[TOCM]

## Utilización
Ingresar a la herramienta desde la barra lateral izquierda clicando en "CSV Importer".

![](https://siscard.com/wp-content/uploads/csv_importer_ingreso.png)

> Ingresar a la herramienta.

### Preparacion del CSV
El formato y el orden de las columnas del archivo deben ser exactamente las mencionadas.

![](https://siscard.com/wp-content/uploads/example_CSV.png)

> Ejemplo del Excel.

1. Desde un archivo excel ordenar la informacion de la siguiente manera:
	1. Columnas: ZONA ID | NOMBRE TECNICO | DNI TECNICO | ZONA DE COBERTURA. 
	2. Formato ZONA ID: Maximo 7 caracteres sin espacios.
	3. NOMBRE TECNICO: Cadena de texto sin límite.
	4. DNI TECNICO: Solamente números sin puntos.
	5. ZONA DE COBERTURA: cadena de texto sin límite.

2. Guardar Como >  formato del archivo: CSV UTF-8 (delimitado por comas).
3. Listo !

### Preparacion imágenes de los técnicos

El sitio web actual permite subir imagenes con un tamaño maximo de 2Mb. Por ende se deben redimensionar para no superar dicho limite. Un sitio gratuito que puede utilizar es el de [Photopea](https://www.photopea.com/)
1. Controlar el tamaño de las imágenes. 
2. El nombre del archivo debe ser la zona id del técnico. Ejemplo Tecnico: Juan Peres, zona id : 1234567, nombre imagen: 1234567.png
3. Formatos admitidos: .png .jpg
4. Listo !

### Cargar Técnicos

Completado del formulario
1. Se debe ingresar en el formulario el slug del CTP. Por defecto viene pre-rellenado con "tecnicos".
2. Los meta_key por defecto vienen pre-rellenados con: "tecnico_dni", "tecnico_zona" y "zona_id".
3. Cargar el archivo .csv, en caso de enviar otro tipo de archivo da error  y los datos pueden estar separados por ; o , 

NOTA:Tener en cuenta que al pasar de un archivo Excel a CSV se debe seleccionar la opcion CSV UTF-8 (delimitado por comas).

![](https://siscard.com/wp-content/uploads/csv_importer_msj_success_tecnicos.png)

> El plugin debe mostrar un mensaje de success con la cantidad de técnicos cargados.


### Asignacion de fotos a cada Técnico

1. Subir imagenes
	1. En el menu Multimedia/Agregar Nueva, subir todas las imágenes de los técnicos.
2. Asignacion de imagenes a los tecnicos
	1. Dentro del plugin CSV Importer hacer click en el segundo boton "Cargar Imagenes". 

![](https://siscard.com/wp-content/uploads/csv_importer_msj_success_imagenes.png)

> El plugin muestra un mensaje de success indicando la cantidad de imagenes asignadas a tecnicos.


------------

## Instalación

### Preparacion del sitio Wordpress
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