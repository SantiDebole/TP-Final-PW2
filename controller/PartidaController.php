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

    public function mostrarPregunta(){

        //buscar pregunta
        $pregunta = $this->model->getPregunta();

        $this->presenter->show("pregunta", ["pregunta" => $pregunta]);
    }

    public function responder(){

    }
}