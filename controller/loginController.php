<?php


class LoginController{

    private $model;
    private $presenter;

    public function __construct($model, $presenter){
        $this->model = $model;
        $this->presenter = $presenter;
    }

    public function validate(){
        echo "hola";
    }

    public function list(){
        $this->presenter->render("./view/loginView.html");
    }
}