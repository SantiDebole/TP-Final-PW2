<?php
session_start();
var_dump($_SESSION);
echo 'ESTE ES EL ID DEL USUARIO: ';
var_dump($_SESSION['loggedUserId']);
//si tenemos la sesion iniciada, deberia ir al lobby
include_once ("./configuration/Configuration.php");

$configuration = new Configuration();

$router = $configuration->getRouter();

$controller = isset($_GET["controller"]) ? $_GET["controller"] : "" ;

$action = isset($_GET["action"]) ? $_GET["action"] : "" ;

$router->route($controller, $action);
