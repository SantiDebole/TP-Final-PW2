<?php
class ReporteController{

    private $model;

    private $presenter;

    public function __construct($model,$presenter){

        $this->model = $model;
        $this->presenter = $presenter;
    }

    public function reportarPregunta(){
        $idPregunta= $_SESSION['idPregunta'];
        $idPartida = $_SESSION["idPartida"];
        $idUsuario = $_SESSION["user_id"];

        $puntajePartida= $this->model->traerPuntajeDelUsuarioEnLaPartida($idPartida,$idUsuario);

        $resultadoDeLaUltimaPregunta = $this->model->verificarPuntajeDeLaUltimaPreguntaConIdPartidaIdUsuarioYIdPregunta($idPartida,$idUsuario, $idPregunta);

        if($resultadoDeLaUltimaPregunta == "respuesta correcta"){
            $data=[
                'preguntaReportada'=>$idPregunta,
                'puntajePartida' => $puntajePartida,
                'resultadoDeLaUltimaPregunta'=>$resultadoDeLaUltimaPregunta
            ];
            $this->presenter->show('reporteCorrecta',$data);

        }else{

            $data=[
                'preguntaReportada'=>$idPregunta,
                'puntajePartida' => $puntajePartida,
                'resultadoDeLaUltimaPregunta'=>$resultadoDeLaUltimaPregunta
            ];
            unset($_SESSION["idPartida"]);
            unset($_SESSION["idPregunta"]);
            $this->presenter->show('reporteIncorrecta',$data);
        }





    }


    public function reportaEnviado(){

        $idPreguntaReportada= $_SESSION['idPregunta'];

        $data=[
            'preguntaReportada'=>$idPreguntaReportada
        ];

        $this->presenter->show('reporteEnviado',$data);

    }

}

