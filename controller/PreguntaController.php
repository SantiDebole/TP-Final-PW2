<?php


class PreguntaController {

    private $model;

    private $presenter;

    public function __construct($model,$presenter){

        $this->model = $model;
        $this->presenter = $presenter;
    }

    public function sugerirPregunta(){
        //$idUsuario = $_SESSION['user_id'];

        //$data['perfil'] = $this->model->traerPerfil($idUsuario);

        $this->presenter->show('sugerirPregunta');
    }

    public function validarPreguntaSugerida(){
        //$idUsuario = $_SESSION['user_id'];

        //$data['perfil'] = $this->model->traerPerfil($idUsuario);

        $this->presenter->show('sugerirPregunta');
    }

}
?>
