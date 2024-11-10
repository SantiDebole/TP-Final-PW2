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
        $preguntaSugerida = isset($_POST['pregunta_sugerida']) ? $_POST['pregunta_sugerida'] : '';
        $respuestaCorrecta = isset($_POST['respuesta_correcta']) ? $_POST['respuesta_correcta'] : '';
        $respuesta_incorrecta_1 = isset($_POST['respuesta_incorrecta_1']) ? $_POST['respuesta_incorrecta_1'] : '';
        $respuesta_incorrecta_2 = isset($_POST['respuesta_incorrecta_2']) ? $_POST['respuesta_incorrecta_2'] : '';
        $estado = "pendiente";

        $this->model->agregarPreguntaConUnaRespuestaCorrectaYDosIncorrectasYCambiarDeEstado($preguntaSugerida,$respuestaCorrecta,$respuesta_incorrecta_1,$respuesta_incorrecta_2,$estado);

        //$data['perfil'] = $this->model->traerPerfil($idUsuario);

        $this->presenter->show('preguntaSugeridaEnviada');
    }

}
?>
