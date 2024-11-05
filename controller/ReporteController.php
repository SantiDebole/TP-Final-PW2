<?php
class ReporteController{

    private $model;

    private $presenter;

    public function __construct($model,$presenter){

        $this->model = $model;
        $this->presenter = $presenter;
    }

    public function reportarPregunta(){
       $idPreguntaReportada= $_POST['idPreguntaReportada'];

       $data=[
           'preguntaReportada'=>$idPreguntaReportada
       ];

        $this->presenter->show('reporte',$data);

    }

}

