<?php

class PartidaController
{
    private $model;
    private $presenter;

    public function __construct($model, $presenter)
    {
        $this->model = $model;
        $this->presenter = $presenter;
    }

    public function jugar(){
        if(isset($_SESSION["loggedUserId"])){
            $idUsuario = $_SESSION["loggedUserId"];
            $pregunta = $this->model->getPregunta($idUsuario);
            if($pregunta){
                if(!isset($_SESSION["idPartida"])){
                    $this->crearPartida($idUsuario);
                }
                $this->presenter->show("pregunta", ["pregunta" => $pregunta]);
            }
            //de momento si no hay mas preguntas, que te mande al lobby. Mas adelante tendria que resetear las preguntasVistas
            header("location: /lobby/listar");
        }
    }

    public function responder(){
        $idRespuesta = $_POST["idRespuesta"];
        $idPregunta = $_POST["idPregunta"];
        $acierta = $this->model->validarRespuesta($idRespuesta);
        if($acierta){
            $idPartida = $_SESSION["idPartida"];
            $this->model->registrarPreguntaCorrecta($idPartida, $idPregunta);
            header("location: /partida/jugar");
            exit();
        }else{
            $idPartida = $_SESSION["idPartida"];
            $this->model->registrarPreguntaIncorrecta($idPartida, $idPregunta);
            $this->model->desactivarPartida($idPartida);
            unset($_SESSION["idPartida"]);
            header("location: /partida/mostrarPuntos");
            exit();
        }
    }

    public function mostrarPuntos(){
        //generaria la vista donde muestra los puntos obtenidos
        $this->presenter->show("mostrarPuntos");
    }

    private function crearPartida($idUsuario){
        $this->model->crearPartida($idUsuario);
        $partida = $this->model->getPartida($idUsuario);
        $_SESSION["idPartida"] = $partida["id"];
    }



}