<?php


class PerfilController {

    private $model;

    private $presenter;

    public function __construct($model,$presenter){

        $this->model = $model;
        $this->presenter = $presenter;
    }

    public function listar(){

        $data['perfil'] = $this->model->traerPerfil();

        $this->presenter->show('perfil',$data);
    }

}
?>
