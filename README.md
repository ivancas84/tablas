# tablas

El objetivo principal de este proyecto es construir una estructura en PHP de la base de datos para ser utilizada en otros proyectos. Partiendo de este objetivo principal se definieron dos subproyectos:

1) PHPMYGEN que define un conjunto de clases para acceder a una base de datos MySQL

2) ANGULARIOGEN que define una serie de templates para facilitar el desarrollo de un frontend en Angular2+.

El proyecto tablas se construye partiendo de la siguiente ideas:
* Una base de datos es el componente principal de todo sistema. No importa cual sea la forma en que se accede a los datos, los datos siempre están, todo lo demás es secundario.
* Los datos y su estructura son dinámicas, varían con el tiempo. Deben utilizarse herramientas que permitan fácilmente adaptarse a los cambios.
* Un motor de base de datos proporciona un conjunto de herramientas que facilitan el desarrollo y no conviene dejarlas a un lado. La solución más óptima se logra utilizando la herramienta adecuada según el problema a resolver.
* La base de datos constituye una poderosa herramienta de configuración, ya que además de resguardar los datos, proporciona un mapa de configuración de sus tipos, nombres, relaciones, etc.


Procedimiento inicial:

Definir un proyecto donde se guardara la estructura: "/path/to/project/"

Definir un archivo de configuracion: "/path/to/project/config/config.php"

Incluir en el archivo de configuracion las siguientes constantes, por ejemplo:

<pre>
//ini_set("display_errors", 1);
//ini_set('date.timezone', 'America/Argentina/Buenos_Aires');
//session_set_cookie_params(7200, '/', '.dominio.com.ar'); //produccion
//session_id(1); //testing

define("SYS_NAME", "Nombre del proyecto"); //nombre del modulo de administracion
define("PATH_SYS", "/path/to/project/"); //path correspondiente al módulo de administración

//constantes de acceso a la base de datos
define("DATA_DBNAME", "dbname");
define("DATA_USER", "user");
define("DATA_PASS", "pass");
define("DATA_HOST", "host");
define("DATA_SCHEMA", "schema");
define("DISABLE_ENTITIES", "tablas a deshabilitar separadas por espacio");

//raiz del modulo de administración
define("PATH_HTTP" , "http://" . $_SERVER["SERVER_NAME"] . "/" . PATH_SYS . "/");
define("PATH_ROOT" ,  $_SERVER["DOCUMENT_ROOT"] . "/" . PATH_SYS . "/");
//define("PATH_LOGIN", "http://" . $_SERVER["SERVER_NAME"] . "/" . PATH_SYS . "/login/"); //path correspondiente al módulo de login

//definición de rutas de inclusión
set_include_path(get_include_path()
  . PATH_SEPARATOR . $_SERVER["DOCUMENT_ROOT"] . "/" . PATH_SYS . "/"
  . PATH_SEPARATOR . $_SERVER["DOCUMENT_ROOT"] . "/" . PATH_SYS . "/api"
  . PATH_SEPARATOR . $_SERVER["DOCUMENT_ROOT"] . "/" . PATH_SYS . "/api/main"
);

Incluir en el index.php la ruta al archivo de configuracion del proyecto
</pre>

# base de datos

* Todas las tablas deben tener un id definido

* Debe existir una tabla transaccion con los siguientes atributos:

id BIGINT(20)
tipo VARCHAR(255)
detalle TEXT
descripcion TEXT
alta TIMESTAMP
actualizado TIMESTAMP
