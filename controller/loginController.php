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
        $usuario = $this->model->validateLogin($username, $password);
        if($usuario){
            $this->presenter->render("./view/lobby.mustache");
        }else{
            header("location: /tp-final-pw2/login/list");
            exit();
        }

    }



    public function list(){
        $this->presenter->render("./view/loginView.mustache");
    }
}