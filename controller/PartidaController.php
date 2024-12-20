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

    public function jugarNuevaPartida()
    {
        if (isset($_SESSION["user_id"])) {
            $idUsuario = $_SESSION["user_id"];

            $this->model->terminarPartida($idUsuario);
            header("Location: /partida/crearPartida ");
        }
    }

    public function crearPartida()
    {
        if (isset($_SESSION["user_id"])) {
            $idUsuario = $_SESSION["user_id"];
            if ($this->model->consultarPartidaDisponible($idUsuario)) {
                $this->presenter->show("reanudarPartida");
            } else {
                $_SESSION['partida'] = $this->model->crearPartida($idUsuario);
                header("Location: /partida/preguntar ");
            }
        }


    }

    public function preguntar(){
        $data = [];
        if (isset($_SESSION["user_id"]) && isset($_SESSION['partida'])) {
            $idUsuario = $_SESSION["user_id"];
            $idPartida = $_SESSION['partida'];

            $preguntaConRespuestas = $this->model->getPreguntaConRespuestas($idUsuario, $idPartida);
            $tiempoRestante = $this->model->tiempoRestanteDeRespuesta($idPartida);
            $puntaje = $this->model->traerPuntajeDelUsuarioEnLaPartida($idPartida, $idUsuario);

            if ($preguntaConRespuestas) {
                $data = [

                    "loggedUserId" => $idUsuario,
                    "pregunta" => $preguntaConRespuestas,
                    "puntaje" => "$puntaje",
                    "tiempoRestante" => $tiempoRestante
                ];
                $this->presenter->show("pregunta", $data);
            }
        }else $this->presenter->show("partidaNoDisponible");
    }
    public function reanudarPartida()
    {
        if (isset($_SESSION["user_id"])) {
            $idUsuario = $_SESSION["user_id"];
            if ($this->model->consultarPartidaDisponible($idUsuario)) {
                $partida= $this->model->tienePartidaDisponible($idUsuario);
                $_SESSION['partida'] = $partida['id'];
                $this->preguntar();
            }else $this->presenter->show("partidaNoDisponible");
        }
    }
    public function responder()
    {
        if (isset($_SESSION["user_id"]) && isset($_SESSION['partida'])) {
            $idUsuario = $_SESSION["user_id"];
            $idPartida = $_SESSION['partida'];
            if (isset($_POST["idRespuesta"])) $idRespuesta = $_POST["idRespuesta"];
            else $idRespuesta=0;

            if ($this->model->validarRespuesta($idRespuesta, $idPartida)) {
                $ultimaPregunta = $this->model->ultimaPregunta($idPartida, $idUsuario);
                $puntaje = $this->model->traerPuntajeDelUsuarioEnLaPartida($idPartida, $idUsuario);
                $data = [
                    "loggedUserId" => $idUsuario,
                    "resultadoCorrecta" => "respuesta correcta",
                    "puntaje" => "$puntaje",
                    "ultimaPregunta" => $ultimaPregunta

                ];
                $this->presenter->show("resultadoRespuesta", $data);
            } else {
                $ultimaPregunta = $this->model->ultimaPregunta($idPartida, $idUsuario);
                $puntaje = $this->model->traerPuntajeDelUsuarioEnLaPartida($idPartida, $idUsuario);
                $data = [
                    "loggedUserId" => $idUsuario,
                    "resultadoIncorrecta" => "respuesta incorrecta",
                    "puntaje" => "$puntaje",
                    "ultimaPregunta" => $ultimaPregunta
                ];
                unset($_SESSION['partida']);
                $this->presenter->show("resultadoRespuesta", $data);

            }


        }else $this->presenter->show("partidaNoDisponible");

    }

}