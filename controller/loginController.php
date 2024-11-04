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
            $usuario = $this->model->getUser($username);
            $loggedUserId = $usuario["id"];
            // Guardo la información del usuario en la sesión
            $_SESSION['user_id'] = $loggedUserId;
            $_SESSION['username'] = $username;
            $_SESSION['rol'] = $usuario["rol"];



            // Redirecciono en base al rol
            header("Location: /lobby/listar");
            exit();



        }else{
            $this->presenter->show("login",["auth_error" => "Error, usuario y/o contraseña incorrectos."]);
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