<?php
class ReporteController{

    private $model;

    private $presenter;

    public function __construct($model,$presenter){

        $this->model = $model;
        $this->presenter = $presenter;
    }

    public function reportarPregunta(){
        var_dump($_SESSION);
       $idPreguntaReportada= $_SESSION['idPregunta'];

       $data=[
           'preguntaReportada'=>$idPreguntaReportada
       ];

        $this->presenter->show('reporte',$data);

    }

    public function reportaEnviado(){
        var_dump($_SESSION);
        $idPreguntaReportada= $_SESSION['idPregunta'];

        $data=[
            'preguntaReportada'=>$idPreguntaReportada
        ];

        $this->presenter->show('reporteEnviado',$data);

    }

}

