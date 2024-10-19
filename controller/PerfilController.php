<?php
session_start();
require './data/db.php'; // Incluir la conexión a la base de datos

class PerfilController {

    private $model;

    private $presenter;

    public function __construct($model,$presenter){

        $this->model = $model;
        $this->presenter = $presenter;
    }

    public function listar(){

        $data = [];

        $this->presenter->show('perfil',$data);
    }

}
?>
