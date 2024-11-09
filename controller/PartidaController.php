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

    public function iniciarPartida()
    {

        if (isset($_SESSION["user_id"])) {
            $idUsuario = $_SESSION["user_id"];
            if ($this->model->consultarPartidaDisponible($idUsuario)) {
                $this->presenter->show("reanudarPartida");
            } else {
                $this->crearPartida();
            }
        }
    }
    public function jugarNuevaPartida(){
        if (isset($_SESSION["user_id"])) {
        $idUsuario = $_SESSION["user_id"];
        $this->model->terminarPartida($idUsuario);
            header("Location: /partida/crearPartida ");
        }
    }


    public function crearPartida(){
        if (isset($_SESSION["user_id"])) {
            $idUsuario = $_SESSION["user_id"];
            if ($this->model->consultarPartidaDisponible($idUsuario)) {
                $this->presenter->show("reanudarPartida");
            } else {
                $_SESSION['partida']= $this->model->crearPartida($idUsuario);
                $preguntaConRespuestas = $this->model->getPreguntaConRespuestas($idUsuario);
                $data = [
                    "loggedUserId" => $idUsuario,
                    "pregunta" => $preguntaConRespuestas,
                    "puntaje" => "0"
                ];
                $this->presenter->show("pregunta", $data);
            }
        }

    }
    public function reanudarPartida(){


}


}


/*
        if(isset($_SESSION["user_id"])){
            $idUsuario = $_SESSION["user_id"];
            $pregunta = $this->model->jugar($idUsuario);
            if($pregunta){
                if(!isset($_SESSION["idPartida"])){
                    $this->crearPartida($idUsuario);
                }
                $idPartida = $_SESSION["idPartida"];
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
        $idUsuario = $_SESSION["user_id"];
        $acierta = $this->model->validarRespuesta($idRespuesta);
        if($acierta){
            $idPartida = $_SESSION["idPartida"];
            $this->model->registrarPreguntaCorrecta($idPartida, $idPregunta);
            $this->model->marcarPreguntaVistaPorUsuario($idUsuario, $idPregunta);

            header("location: /partida/jugar");
            exit();
        }else{
            $idPartida = $_SESSION["idPartida"];
            $this->model->registrarPreguntaIncorrecta($idPartida, $idPregunta);
            $this->model->marcarPreguntaVistaPorUsuario($idUsuario, $idPregunta);
            $this->model->desactivarPartida($idPartida);


            // se destruye la variable de la sesion que contiene el id de la partida actual al finalizar la partida.

            header("location: /partida/mostrarPuntos");
            exit();
        }
    }

    public function mostrarPuntos(){
        //generaria la vista donde muestra los puntos obtenidos
        $idUsuario = $_SESSION["user_id"];
        $idPartida = $_SESSION["idPartida"];
        $puntaje= $this->model->traerPuntajeDelUsuarioEnLaPartida($idPartida,$idUsuario);
        $data = [
            "loggedUserId" => $idUsuario,
            "puntaje" => $puntaje
        ];
        unset($_SESSION["idPartida"]);
        $this->presenter->show("mostrarPuntos",$data);
    }

    private function crearPartida($idUsuario){
        $this->model->crearPartida($idUsuario);
        $partida = $this->model->getPartida($idUsuario);
        $_SESSION["idPartida"] = $partida["id"];
    }





}*/