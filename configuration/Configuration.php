<?php
/*PONER INCLUDES Y REQUIRES ACA*/
include_once ("./helper/MustachePresenter.php");
include_once ("./helper/Database.php");
include_once ("./helper/Router.php");

include_once ("./controller/loginController.php");
include_once ("./model/loginModel.php");

include_once ("./controller/RegistroController.php");

include_once ("./model/RegistroModel.php");

include_once('./vendor/autoload.php');
class Configuration
{
    public function __construct()
    {


    }

    public function getRouter(){
        return new Router($this,"getLoginController", "listar");
    }

    public function getDatabase(){
        $config = parse_ini_file("./configuration/config.ini");
        return new Database($config['host'], $config['username'], $config['password'], $config['database'], $config['port']);


    }

    private static function getPresenter()
    {
        return new MustachePresenter("./view/template");
    }
    // getCONTROLLERS
    public function getLoginController(){
        return new LoginController($this->getLoginModel(),$this->getPresenter());
    }


    // getMODELS
    private function getLoginModel(){
        return new LoginModel($this->getDatabase());
    }

    private function getPerfilController(){
        return new PerfilController($this->getPerfilModel(),$this->getPresenter);
    }

    private function getPerfilModel(){
        return new PerfilModel($this->getDatabase());
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



}