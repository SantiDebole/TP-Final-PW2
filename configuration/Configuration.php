<?php
/*PONER INCLUDES Y REQUIRES ACA*/
include_once ("./helper/MustachePresenter.php");
include_once ("./helper/Database.php");
include_once ("./helper/Router.php");
include_once ("./vendor/mustache/src/Mustache/Source/Autoloader.php");
class Configuration
{

    // getCONTROLLERS


    // getMODELS



    // getHELPERS

    public static function getDatabase()
    {
        $config = self::getConfig();
        return new Database($config["servername"], $config["username"], $config["password"], $config["dbname"]);
    }




    public static function getRouter()
    {
        return new Router("nombreDelMetodoDeConfigurationQueLlamaAUnControllerPorDefault", "list");
    }

    private static function getPresenter()
    {
        return new MustachePresenter("view/template");
    }

    //OTROS
    private static function getConfig()
    {
        return parse_ini_file("configuration/config.ini");
    }
}