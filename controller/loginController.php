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
            $loggedUserId = $_SESSION["loggedUserId"];
            $rol = $this->model->getUserRol($loggedUserId);

            // Guardo la información del usuario en la sesión
            $_SESSION['user_id'] = $loggedUserId;
            $_SESSION['username'] = $username;
            $_SESSION['rol'] = $rol;


            // Redirecciono en base al rol
            header("Location: /lobby/listar");
            exit();



        }else{
            $this->presenter->show("login",["auth_error" => $_SESSION["auth_error"]]);
        }

    }

    public function logout(){

        session_destroy();
        header("Location: /login/listar");
        exit();


    }



    public function listar(){
        $this->presenter->show("login");
    }
}