<?php

class EditorController
{
    private $model;

    private $presenter;

    public function __construct($model,$presenter){

        $this->model = $model;
        $this->presenter = $presenter;
    }
    public function preguntasReportadas() {
        try {
            $preguntasReportadas = $this->model->obtenerPreguntasReportadas();
            $this->presenter->show('preguntasReportadas', ['preguntasReportadas' => $preguntasReportadas]);
        } catch (Exception $e) {
            $this->presenter->show('error', ['mensajeError' => "No se pudieron obtener las preguntas reportadas: " . $e->getMessage()]);
        }
    }

    public function manejoAccionReporte()
    {
        if (isset($_POST['accion']) && isset($_POST['idPregunta'])) {
            $accion = $_POST['accion'];
            $idPregunta = $_POST['idPregunta'];


            if ($accion === 'darDeAlta') {
                $resultado = $this->model->darDeAltaReporte($idPregunta);
            } elseif ($accion === 'darDeBaja') {
                $resultado = $this->model->darDeBajaReporte($idPregunta);
            }


            if ($resultado) {

                header("Location: /editor/preguntasReportadas");
                exit;
            } else {

                echo "Error al procesar la solicitud.";
            }
        }
    }
}