# plugin_import_csv
Plugin desarrollado para leer un .csv y que guarde en la DB de Wordpress como post los datos que estan dentro del .csv

## Primer paso
Se debe ingresar en el formulario el slug del CTP y los meta_key que se desea cargar. Tener en cuenta que ésta version del plugin acepta unicamente 2 meta_keys de tipo string.

## Segundo paso
Cargar el archivo .csv, en caso de enviar otro tipo de archivo da error, y los datos pueden estar separados por ; o ,. 

## Tercer paso
La funcion intermanete BORRA los post existentes y graba unicamente los subidos en el archivo. Tener ésto en cuenta ya que se puede perder información valiosa.

## Cuarto paso
Contactarme y realizar una donación :P
