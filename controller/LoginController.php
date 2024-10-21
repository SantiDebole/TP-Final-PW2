<?php


class LoginController{

    private $model;
    private $presenter;

    public function __construct($model, $presenter){
        $this->model = $model;
        $this->presenter = $presenter;
    }

    public function validate(){
        var_dump($_POST);
        $username = $_POST['username'];
        $password = $_POST['password'];
        $esValido = $this->model->validateLogin($username, $password);
        if($esValido){
            $loggedUserId = $_SESSION["loggedUserId"] ? $_SESSION["loggedUserId"] : null;

            $data = [
                'lobby' => [
                    "loggedUserId" => $loggedUserId,
                    "username" => $username
                ]
            ];
            $_SESSION['lobby'] = $data;

            //$_SESSION['lobby']['username']

            //$this->presenter->show("lobby", $data);

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