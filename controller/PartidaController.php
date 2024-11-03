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
                $partida = $this->model->getPartida($idUsuario);
                if(!isset($partida)){
                    $this->crearPartida($idUsuario);
                    $partida = $this->model->getPartida($idUsuario);
                }
                $idPartida = $partida["id"];
                $puntaje= $this->model->traerPuntajeDelUsuarioEnLaPartida($idPartida,$idUsuario);
                $data = [
                    "loggedUserId" => $idUsuario,
                    "pregunta" => $pregunta,
                    "puntaje" => $puntaje
                ];
                $this->presenter->show("pregunta", $data);
            }else{
                $this->model->reiniciarRegistroDePreguntasVistasPorUsuario($idUsuario);
                header("Location: /partida/jugar");
                exit();
            }

        }
    }


    public function responder(){
        $idRespuesta = $_POST["idRespuesta"];
        $idPregunta = $_POST["idPregunta"];
        $idUsuario = $_SESSION["loggedUserId"];
        $acierta = $this->model->validarRespuesta($idRespuesta);
        if($acierta){
            $partida = $this->model->getPartida($idUsuario);
            $idPartida = $partida["id"];
            $this->model->registrarPreguntaCorrecta($idPartida, $idPregunta);
            $this->model->marcarPreguntaVistaPorUsuario($idUsuario, $idPregunta);

            header("location: /partida/jugar");
            exit();
        }else{
            $partida = $this->model->getPartida($idUsuario);
            $idPartida = $partida["id"];
            $this->model->registrarPreguntaIncorrecta($idPartida, $idPregunta);
            $this->model->marcarPreguntaVistaPorUsuario($idUsuario, $idPregunta);
            header("location: /partida/mostrarPuntos");
            exit();
        }
    }

    public function mostrarPuntos(){
        //generaria la vista donde muestra los puntos obtenidos
        $idUsuario = $_SESSION["loggedUserId"];
        $partida = $this->model->getPartida($idUsuario);
        $idPartida = $partida["id"];
        $puntaje= $this->model->traerPuntajeDelUsuarioEnLaPartida($idPartida,$idUsuario);
        $data = [
            "loggedUserId" => $idUsuario,
            "puntaje" => $puntaje
        ];
        $this->model->desactivarPartida($idPartida);
        $this->presenter->show("mostrarPuntos",$data);
    }

    private function crearPartida($idUsuario){
        $this->model->crearPartida($idUsuario);
    }





}