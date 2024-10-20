<?php


class LoginController{

    private $model;
    private $presenter;

    public function __construct($model, $presenter){
        $this->model = $model;
        $this->presenter = $presenter;
    }

    public function validate(){
        $username = $_POST['username'];
        $password = $_POST['password'];
        $esValido = $this->model->validateLogin($username, $password);
        if($esValido){
            $this->presenter->show("lobby",["loggedUserId" => $_SESSION["loggedUserId"]]);
        }else{
            $this->presenter->show("login",["auth_error" => $_SESSION["auth_error"]]);
        }

    }



    public function listar(){
        $this->presenter->show("login");
    }
}