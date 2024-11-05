<?php

//mustache
include_once('./vendor/autoload.php');

//helpers
include_once ("./helper/MustachePresenter.php");
include_once ("./helper/Database.php");
include_once ("./helper/Router.php");
include_once ("./helper/Mailer.php");
include_once ("./helper/GeneradorDeQR.php");
//model
include_once ("./model/RegistroModel.php");
include_once("./model/LoginModel.php");
include_once ("./model/LobbyModel.php");
include_once ("./model/PerfilModel.php");

include_once ("./model/PartidaModel.php");
include_once ("./model/ReporteModel.php");


//controladores
include_once("./controller/LoginController.php");
include_once ("./controller/RegistroController.php");
include_once ("./controller/LobbyController.php");
include_once ("./controller/PerfilController.php");
include_once ("./controller/PartidaController.php");
include_once ("./controller/RegistroController.php");



class Configuration
{
    public function __construct()
    {


    }

    public function getRouter(){
        if(isset($_SESSION['rol'])){
            return new Router($this,"getLobbyController", "listar");
            }else  return new Router($this,"getLoginController", "listar");

    }




    public function getDatabase(){
        $config = parse_ini_file("./configuration/config.ini");
        return new Database($config['host'], $config['username'], $config['password'], $config['database'], $config['port']);


    }

    public function getPresenter()
    {
        return new MustachePresenter("./view/template");
    }
    // getCONTROLLERS

    public function getReporteController(){
        return new RegistroController($this->getReporteModel(),$this->getPresenter());
    }

    // getMODELS
    public function getReporteModel(){
        return new ReporteModel($this->getDatabase());
    }
    public function getLoginController(){
        return new LoginController($this->getLoginModel(),$this->getPresenter());
    }

    // getMODELS
    public function getLoginModel(){
        return new LoginModel($this->getDatabase());
    }

    public function getLobbyController(){
        return new LobbyController($this->getLobbyModel(),$this->getPresenter());
    }

    public function getLobbyModel(){
        return new LobbyModel($this->getDatabase());
    }

    public function getPerfilController(){
        return new PerfilController($this->getPerfilModel(),$this->getPresenter());
    }

    public function getPerfilModel(){
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


    public function getPartidaController()
    {
        return new PartidaController($this->getPartidaModel(), $this->getPresenter());
    }

    ///MODELO REGISTRO
    public function getPartidaModel(){
        return new PartidaModel($this->getDatabase());
    }


}