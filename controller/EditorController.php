<?php

class EditorController
{
    private $model;
    private $presenter;

    public function __construct($model, $presenter)
    {
        $this->model = $model;
        $this->presenter = $presenter;
    }

    public function preguntasReportadas()
    {
        try {
            $preguntasReportadas = $this->model->obtenerPreguntasReportadas();
            foreach ($preguntasReportadas as &$pregunta) {
                $respuestas = $this->model->obtenerRespuestasDeUnaPregunta($pregunta['id_pregunta']);
                if (!empty($respuestas)) {
                    $pregunta['respuestas'] = $respuestas;
                }
            }
            $this->presenter->show('preguntasReportadas', ['preguntasReportadas' => $preguntasReportadas]);
        } catch (Exception $e) {
            $this->presenter->show('error', ['mensajeError' => "No se pudieron obtener las preguntas reportadas: " . $e->getMessage()]);
        }
    }

    public function manejoAccionReporte()
    {
        try {
            if (isset($_POST['accion']) && isset($_POST['idPregunta'])) {
                $accion = $_POST['accion'];
                $idPregunta = $_POST['idPregunta'];

                $this->validarAccion($accion);
                $this->validarIdPregunta($idPregunta);

                $resultado = ($accion === 'darDeAlta')
                    ? $this->model->darDeAltaReporte($idPregunta)
                    : $this->model->darDeBajaReporte($idPregunta);

                if ($resultado) {
                    $this->redirigirAPreguntasReportadas();
                } else {
                    throw new Exception("Error al procesar la solicitud.");
                }
            } else {
                throw new Exception("Datos incompletos.");
            }
        } catch (Exception $e) {
            $this->presenter->show('error', ['mensajeError' => $e->getMessage()]);
        }
    }

    public function modificarPreguntaYORespuestas()
    {
        try {
            $idPregunta = $_POST['idPregunta'];
            if (isset($_POST['modificarPregunta']) && $_POST['modificarPregunta'] === 'true') {
                $textoPregunta = $_POST['textoPregunta'];
                $idCategoria = $_POST['idCategoria'];

                $this->validarDatosPregunta($idPregunta, $textoPregunta, $idCategoria);

                $preguntaActual = $this->model->obtenerPreguntaPorId($idPregunta);
                if ($preguntaActual['texto'] !== $textoPregunta || $preguntaActual['categoria_id'] !== $idCategoria) {
                    $this->model->modificarPregunta($idPregunta, $textoPregunta, $idCategoria);
                }
            }

            if (isset($_POST['respuestas'])) {
                foreach ($_POST['respuestas'] as $respuestaData) {
                    $this->validarRespuesta($respuestaData);
                    $respuestaActual = $this->model->obtenerRespuestaPorId($respuestaData['id_respuesta']);
                    if ($respuestaActual['descripcion'] !== $respuestaData['descripcion']) {
                        $this->model->modificarRespuesta($respuestaData['id_respuesta'], $respuestaData['descripcion']);
                    }
                }
            }
            $this->redirigirAPreguntasReportadas();
        } catch (Exception $e) {
            $this->presenter->show('error', ['mensajeError' => "Error al modificar la pregunta o respuestas: " . $e->getMessage()]);
        }
    }

    public function mostrarFormularioEdicionPregunta()
    {
        try {
            if (isset($_POST['idPregunta'])) {
                $idPregunta = $_POST['idPregunta'];
                $pregunta = $this->model->obtenerPreguntaPorId($idPregunta);
                $respuestas = $this->model->obtenerRespuestasDeUnaPregunta($idPregunta);

                $respuestasCorrecta = [];
                $respuestasIncorrectas = [];

                foreach ($respuestas as $respuesta) {
                    if ($respuesta['es_correcta']) {
                        $respuestasCorrecta[] = $respuesta;
                    } else {
                        $respuestasIncorrectas[] = $respuesta;
                    }
                }

                $respuestas = array_merge($respuestasCorrecta, $respuestasIncorrectas);

                foreach ($respuestas as $index => $respuesta) {
                    $respuestas[$index]['isFirstResponse'] = ($index == 0);
                }

                $data = [
                    'id_pregunta' => $pregunta['id'],
                    'texto_pregunta' => $pregunta['descripcion'],
                    'respuestas' => $respuestas,
                    'isCategoria1' => $pregunta['idCategoria'] == 1,
                    'isCategoria2' => $pregunta['idCategoria'] == 2,
                    'isCategoria3' => $pregunta['idCategoria'] == 3,
                    'isCategoria4' => $pregunta['idCategoria'] == 4,
                ];

                $this->presenter->show('modificarPreguntaReportada', $data);
            }
        } catch (Exception $e) {
            $this->presenter->show('error', ['mensajeError' => "No se pudo cargar la pregunta: " . $e->getMessage()]);
        }
    }

    // Métodos de validación privados
    private function validarAccion($accion)
    {
        if (!in_array($accion, ['darDeAlta', 'darDeBaja'])) {
            throw new Exception("Acción no válida.");
        }
    }

    private function validarIdPregunta($idPregunta)
    {
        if (!filter_var($idPregunta, FILTER_VALIDATE_INT) || $idPregunta <= 0) {
            throw new Exception("ID de pregunta no válida.");
        }
    }

    private function validarDatosPregunta($idPregunta, $textoPregunta, $idCategoria)
    {
        if (!filter_var($idPregunta, FILTER_VALIDATE_INT) || $idPregunta <= 0) {
            throw new Exception("ID de pregunta no válida.");
        }

        if (empty($textoPregunta) || strlen($textoPregunta) > 255) {
            throw new Exception("El texto de la pregunta es inválido o demasiado largo.");
        }

        if (!in_array($idCategoria, [1, 2, 3, 4])) {
            throw new Exception("Categoría no válida.");
        }
    }

    private function validarRespuesta($respuestaData)
    {
        if (empty($respuestaData['descripcion']) || strlen($respuestaData['descripcion']) > 255) {
            throw new Exception("Descripción de la respuesta inválida.");
        }
    }

    // Redirigir a preguntas reportadas
    private function redirigirAPreguntasReportadas()
    {
        header("Location: /editor/preguntasReportadas");
        exit();
    }

}