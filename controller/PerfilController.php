<?php


class PerfilController {

    private $model;

    private $presenter;

    public function __construct($model,$presenter){

        $this->model = $model;
        $this->presenter = $presenter;
    }

    public function listar(){
        $idUsuario = $_SESSION['user_id'];


        $data['perfil'] = $this->model->traerPerfil($idUsuario);

        $this->presenter->show('perfil',$data);
    }

}
?>
