 <?php
session_start();
echo '<h2>ESTA ES LA SESION:</h2>';
var_dump($_SESSION);
echo '<br>';

echo '<br>';

echo '<br>';

//si tenemos la sesion iniciada, deberia ir al lobby
include_once ("./configuration/Configuration.php");

$configuration = new Configuration();

$router = $configuration->getRouter();



$controller = isset($_GET["controller"]) ? $_GET["controller"] : "" ;


$action = isset($_GET["action"]) ? $_GET["action"] : "" ;

$param =  isset($_GET["param"])? $_GET["param"]:"";



$router->route($controller, $action, $param);

