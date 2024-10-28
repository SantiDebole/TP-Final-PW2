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
                $idPartida = $_SESSION["idPartida"];
                $puntaje= $this->model->traerPuntajeDelUsuarioEnLaPartida($idPartida,$idUsuario);
                $data = [
                    "loggedUserId" => $idUsuario,
                    "pregunta" => $pregunta,
                    "puntaje" => $puntaje
                ];
                $this->presenter->show("pregunta", $data);
            }else{
                header("location: /lobby/listar");
                exit();
            }
            //de momento si no hay mas preguntas, que te mande al lobby. Mas adelante tendria que resetear las preguntasVistas

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


            // se destruye la variable de la sesion que contiene el id de la partida actual al finalizar la partida.

            header("location: /partida/mostrarPuntos");
            exit();
        }
    }

    public function mostrarPuntos(){
        //generaria la vista donde muestra los puntos obtenidos
        $idUsuario = $_SESSION["loggedUserId"];
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





}