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
                'idPregunta'=>$idPregunta,
                'puntajePartida' => $puntajePartida,
                'resultadoDeLaUltimaPregunta'=>$resultadoDeLaUltimaPregunta,
                'idUsuario'=>$idUsuario
            ];
            $this->presenter->show('reporteCorrecta',$data);

        }else{

            $data=[
                'idPregunta'=>$idPregunta,
                'puntajePartida' => $puntajePartida,
                'resultadoDeLaUltimaPregunta'=>$resultadoDeLaUltimaPregunta,
                'idUsuario'=>$idUsuario
            ];
            //unset($_SESSION["idPartida"]);
            //unset($_SESSION["idPregunta"]);
            $this->presenter->show('reporteIncorrecta',$data);
        }





    }


    public function reporteEnviadoCorrecta(){

        $idPreguntaReportada= $_SESSION['idPregunta'];
        $idUsuarioQueReporta= $_SESSION["user_id"];
        $textoDelReporte = $_POST["textoReportePregunta"];
        $this->model->reportarPreguntaYCambiarEstado($idPreguntaReportada,$idUsuarioQueReporta,$textoDelReporte);
        $data=[
            'preguntaReportada'=>$idPreguntaReportada,
            'idUsuarioQueReporta'=>$idUsuarioQueReporta
        ];

        $this->presenter->show('reporteEnviadoCorrecta',$data);

    }

    public function reporteEnviadoIncorrecta(){

        $idPreguntaReportada= $_SESSION['idPregunta'];
        $idUsuarioQueReporta= $_SESSION["user_id"];
        $textoDelReporte = $_POST["textoReportePregunta"];
        $this->model->reportarPreguntaYCambiarEstado($idPreguntaReportada,$idUsuarioQueReporta,$textoDelReporte);
        $data=[
            'preguntaReportada'=>$idPreguntaReportada,
            'idUsuarioQueReporta'=>$idUsuarioQueReporta
        ];

        $this->presenter->show('reporteEnviadoIncorrecta',$data);

    }

}

