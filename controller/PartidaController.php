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
            $pregunta = $this->model->getPregunta();
            if(!isset($_SESSION["idPartida"])){
                //la idea seria que si no hay una variable de sesion que contenga el id de la partida, que la cree
                //y en algun momento destruya dicha variable cuando la partida finalice
                $this->crearPartida($idUsuario);
            }

                $this->presenter->show("pregunta", ["pregunta" => $pregunta]);

        }
    }

    public function responder(){
        $idRespuesta = $_POST["idRespuesta"];
        $idPregunta = $_POST["idPregunta"];
        $acierta = $this->model->validarRespuesta($idRespuesta);
        if($acierta){
            //crearia un registro de la tabla "tiene" con el id de la partida y el id de la pregunta,
            // el puntaje no sabria que ponerle
            $idPartida = $_SESSION["idPartida"];
            $_SESSION["correcta"] = 1;
            $this->model->registrarPreguntaCorrecta($idPartida, $idPregunta);
            header("location: /partida/jugar");
            exit();
        }else{
            $idPartida = $_SESSION["idPartida"];
            $this->model->registrarPreguntaIncorrecta($idPartida, $idPregunta);
            // habria que cambiar el estado de la partida a inactivo..

            //le cambia el estado de "activo" a "inactivo" para que al crear una partida
            $this->model->desactivarPartida($idPartida);

            // se destruye la variable de la sesion que contiene el id de la partida actual al finalizar la partida.
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
        //aca capaz para encontrar la partida actual se base en el estado de la partida (?
        $partida = $this->model->getPartida($idUsuario);
        //crearia la variable de sesion idPartida para poder luego en "responder" crear un registro de la tabla "tiene" con el id de
        //la partida que estoy jugando ahora junto con el id de la pregunta
        $_SESSION["idPartida"] = $partida["id"];
    }



}