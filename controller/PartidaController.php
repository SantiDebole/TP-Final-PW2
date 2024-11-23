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

        if(isset($_SESSION["user_id"])){
            $idUsuario = $_SESSION["user_id"];
            $pregunta = $this->model->getPregunta($idUsuario);
            if($pregunta){
                if(!isset($_SESSION["idPartida"])){
                    $this->crearPartida($idUsuario);
                }
                $idPartida = $_SESSION["idPartida"];
                $puntaje= $this->model->traerPuntajeDelUsuarioEnLaPartida($idPartida,$idUsuario);
                $color = $pregunta["pregunta"]["color_categoria"];
                $categoria_descripcion = $pregunta["pregunta"]["categoria_descripcion"];
                $data = [
                    "loggedUserId" => $idUsuario,
                    "pregunta" => $pregunta,
                    "puntaje" => $puntaje,
                    "color" => $color,
                    "categoria_descripcion" => $categoria_descripcion
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
        $_SESSION['idPregunta']=$idPregunta;

        $idUsuario = $_SESSION["user_id"];

        $idPartida = $_SESSION["idPartida"];


        $acierta = $this->model->validarRespuesta($idRespuesta);
        if($acierta){
            $this->model->registrarPreguntaCorrecta($idPartida, $idPregunta);
            $this->model->marcarPreguntaVistaPorUsuario($idUsuario, $idPregunta);
            header("location: /reporte/reportarPregunta");
            exit();
            //header("location: /partida/jugar");
            //exit();
        }else{
            $this->model->registrarPreguntaIncorrecta($idPartida, $idPregunta);
            $this->model->marcarPreguntaVistaPorUsuario($idUsuario, $idPregunta);
            $this->model->desactivarPartida($idPartida);
            // se destruye la variable de la sesion que contiene el id de la partida actual al finalizar la partida.
            //unset($_SESSION["idPartida"]);
            header("location: /reporte/reportarPregunta");
            exit();
            //header("location: /partida/mostrarPuntos");

        }
    }

    /*public function salirDelJuego(){

        $idPartida = $_SESSION["idPartida"];
        $this->model->desactivarPartida($idPartida);
        unset($_SESSION["idPartida"]);
        header("location: /lobby/listar");
        exit();
    }*/

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
    

}