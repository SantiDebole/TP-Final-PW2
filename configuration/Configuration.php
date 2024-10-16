<?php
/*PONER INCLUDES Y REQUIRES ACA*/
include_once ("./helper/MustachePresenter.php");
include_once ("./helper/Database.php");
include_once ("./helper/Router.php");
include_once ("./vendor/mustache/mustache/src/Mustache/Autoloader.php");
include_once ("./controller/loginController.php");
include_once ("./model/loginModel.php");
class Configuration
{
    private $conn;

    public function __construct(){
        $this->conn = $this->getDatabase();
    }

    // getCONTROLLERS
    public function getLoginController(){
        return new LoginController($this->getLoginModel(),$this->getPresenter());
    }



    // getMODELS
    private function getLoginModel(){
        return new LoginModel($this->conn);
    }


    // getHELPERS

    private function getDatabase(){
        $config = $this->getConfig();
        return new Database($config["servername"], $config["username"], $config["password"], $config["dbname"]);
    }




    public function getRouter(){
        return new Router($this,"getLoginController", "list");
    }

    private function getPresenter(){
        return new MustachePresenter("view/template");
    }

    //OTROS
    private function getConfig(){
        return parse_ini_file("./configuration/config.ini");
    }


}