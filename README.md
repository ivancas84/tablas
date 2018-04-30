# tablas

Genera una estructura en PHP de la base de datos, para ser utilizada en otros proyectos PHP.

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








