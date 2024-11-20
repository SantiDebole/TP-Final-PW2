<?php
class ReporteController{

    private $model;

    private $presenter;

    public function __construct($model,$presenter){

        $this->model = $model;
        $this->presenter = $presenter;
    }


    public function reporteEnviado(){

        $idPreguntaReportada= $_POST['idPregunta'];
        $idUsuarioQueReporta= $_POST["idUsuario"];
        $textoDelReporte = $_POST["textoReportePregunta"];
        $this->model->reportarPreguntaYEnviarlaALaTablaReporte($idPreguntaReportada,$idUsuarioQueReporta,$textoDelReporte);
        $data=[
            'preguntaReportada'=>$idPreguntaReportada,
            'idUsuarioQueReporta'=>$idUsuarioQueReporta
        ];

        $this->presenter->show('reporteEnviado',$data);

    }



}

