<?php

class PreguntaController
{
    private $model;
    private $presenter;

    public function __construct($model, $presenter)
    {
        $this->model = $model;
        $this->presenter = $presenter;
    }

    // Método para procesar la creación de una pregunta y sus respuestas
    public function crearPregunta()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $descripcion = $_POST['descripcion'];
            $estado = 'activo';
            $idCategoria = $_POST['idCategoria'] ?? null;

            $respuestas = [
                ['descripcion' => $_POST['respuesta1'], 'esCorrecta' => 1],
                ['descripcion' => $_POST['respuesta2'], 'esCorrecta' => 0],
                ['descripcion' => $_POST['respuesta3'], 'esCorrecta' => 0],
                ['descripcion' => $_POST['respuesta4'], 'esCorrecta' => 0],
            ];

            if ($this->model->crearPregunta($descripcion, $estado, $idCategoria, $respuestas)) {
                $this->presenter->show("preguntaEnviada", ["mensaje" => "Pregunta enviada con éxito"]);
            } else {
                $this->presenter->show("error", ["mensaje" => "Error al crear la pregunta"]);
            }
        }
    }

    // Método para mostrar una pregunta con sus respuestas
    public function mostrarPregunta($idPregunta)
    {
        $pregunta = $this->model->obtenerPreguntaConRespuestas($idPregunta);

        if ($pregunta) {
            $this->presenter->show("detallePregunta", ["pregunta" => $pregunta]);
        } else {
            $this->presenter->show("error", ["mensaje" => "Pregunta no encontrada"]);
        }
    }
}
