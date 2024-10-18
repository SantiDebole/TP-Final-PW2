<?php
/*PONER INCLUDES Y REQUIRES ACA*/
include_once ("./helper/MustachePresenter.php");
include_once ("./helper/Database.php");
include_once ("./helper/Router.php");


include_once ("./controller/RegistroController.php");

include_once ("./model/RegistroModel.php");

include_once('./vendor/autoload.php');
class Configuration
{
    public function __construct(){

    }

    public function getDatabase(){
        $config = parse_ini_file("./configuration/config.ini");
        return new Database($config['host'], $config['username'], $config['password'], $config['database'], $config['port']);

    }

    private static function getPresenter()
    {
        return new MustachePresenter("./view/template");
    }

    //OTROS
    private static function getConfig()
    {
        return parse_ini_file("configuration/config.ini");
    }


    ///CONTROLADOR REGISTRO
    public function getRegistroController()
    {
        return new RegistroController($this->getRegistroModel(), $this->getPresenter());
    }

    ///MODELO REGISTRO
    public function getRegistroModel(){
        return new RegistroModel($this->getDatabase());
    }

    public function getRouter(){

        return new Router($this,"getRegistroController", "listar"); //le paso el objeto configuration con un metodo predeterminado
    }



}