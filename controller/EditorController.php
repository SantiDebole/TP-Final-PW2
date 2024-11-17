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

                // Si las respuestas existen para esa pregunta, se las asigna.
                if (!empty($respuestas)) {
                    $pregunta['respuestas'] = $respuestas;
                }
            }

            // Paso las preguntas reportadas con las respuestas a la vista
            $this->presenter->show('preguntasReportadas', ['preguntasReportadas' => $preguntasReportadas]);
        } catch (Exception $e) {
            // Si hay un error, muestro la vista del error
            $this->presenter->show('error', ['mensajeError' => "No se pudieron obtener las preguntas reportadas: " . $e->getMessage()]);
        }
    }

    public function manejoAccionReporte()
    {
        try {
            if (isset($_POST['accion']) && isset($_POST['idPregunta'])) {
                $accion = $_POST['accion'];
                $idPregunta = $_POST['idPregunta'];

                // Valido la accion elegida
                if (!in_array($accion, ['darDeAlta', 'darDeBaja'])) {
                    throw new Exception("Acción no válida.");
                }

                // Valido la ID de la pregunta (que sea entero y positivo)
                if (!filter_var($idPregunta, FILTER_VALIDATE_INT) || $idPregunta <= 0) {
                    throw new Exception("ID de pregunta no válida.");
                }

                if ($accion === 'darDeAlta') {
                    $resultado = $this->model->darDeAltaReporte($idPregunta);
                } elseif ($accion === 'darDeBaja') {
                    $resultado = $this->model->darDeBajaReporte($idPregunta);
                }

                if ($resultado) {
                    header("Location: /editor/preguntasReportadas");
                    exit;
                } else {
                    throw new Exception("Error al procesar la solicitud.");
                }
            } else {
                throw new Exception("Datos incompletos.");
            }
        } catch (Exception $e) {
            // Si hay un error, muestro vista error
            $this->presenter->show('error', ['mensajeError' => $e->getMessage()]);
        }
    }

    // Procesa la modificacion
    public function modificarPreguntaYORespuestas()
    {
        try {
            if (isset($_POST['idPregunta']) && isset($_POST['textoPregunta']) && isset($_POST['idCategoria']) && isset($_POST['respuestas'])) {
                $idPregunta = $_POST['idPregunta'];
                $textoPregunta = $_POST['textoPregunta'];
                $idCategoria = $_POST['idCategoria'];
                $respuestas = $_POST['respuestas'];  // Las respuestas enviadas


                // Validaciones generales
                $this->validarDatosPregunta($idPregunta, $textoPregunta, $idCategoria);

                // Obtener la pregunta antes de modificarla
                $preguntaOriginal = $this->model->obtenerPreguntaPorId($idPregunta);

                // Verifico si modifiqué la pregunta
                $modificoPregunta = $this->modificoPregunta($preguntaOriginal, $textoPregunta, $idCategoria);

                // Si hubo cambios, modifico la pregunta
                if ($modificoPregunta) {
                    $this->modificarPregunta($idPregunta, $textoPregunta, $idCategoria);
                }

                // Modificar las respuestas si han cambiado
                $modificoAlgunaRespuesta = $this->modificarRespuestas($respuestas);

                // Redirigir si no hubo cambios en la pregunta ni en las respuestas
                if (!$modificoPregunta && !$modificoAlgunaRespuesta) {
                    header("Location: /editor/preguntasReportadas");
                    exit;
                }

                // Redirigir al listado de preguntas reportadas después de modificar
                header("Location: /editor/preguntasReportadas");
                exit;

            } else {
                throw new Exception("Datos incompletos.");
            }

        } catch (Exception $e) {
            // Si hay un error, mostrar mensaje de error
            $this->presenter->show('error', ['mensajeError' => $e->getMessage()]);
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

    private function modificoPregunta($preguntaOriginal, $textoPregunta, $idCategoria)
    {
        // Verificar si la pregunta ha cambiado
        return ($preguntaOriginal['descripcion'] !== $textoPregunta || $preguntaOriginal['idCategoria'] !== $idCategoria);
    }

    private function modificarPregunta($idPregunta, $textoPregunta, $idCategoria)
    {
        // Verificamos si la pregunta ha cambiado antes de realizar la actualización
        $preguntaOriginal = $this->model->obtenerPreguntaPorId($idPregunta);

        // Si no hay cambios en la pregunta ni en la categoría, no hacemos nada
        if ($preguntaOriginal['descripcion'] === $textoPregunta && $preguntaOriginal['idCategoria'] === $idCategoria) {
            return true; // No hubo cambios
        }

        // Si el texto de la pregunta está vacío, no debería actualizarla
        if (empty($textoPregunta)) {
            throw new Exception("El texto de la pregunta no puede estar vacío.");
        }

        // Realizamos la modificación de la pregunta
        $resultado = $this->model->modificarPregunta($idPregunta, $textoPregunta, $idCategoria);

        return true; // Modificación exitosa
    }


    private function modificarRespuestas($respuestas)
    {
        $modificoAlgunaRespuesta = false;
        foreach ($respuestas as $respuesta) {
            // Solo actualizar si el texto de la respuesta ha cambiado
            $respuestaOriginal = $this->model->obtenerRespuestaPorId($respuesta['id_respuesta']);
            if ($respuestaOriginal && $respuestaOriginal['descripcion'] !== $respuesta['descripcion']) {
                $resultado = $this->model->modificarRespuesta($respuesta['id_respuesta'], $respuesta['descripcion']);
                if (!$resultado) {
                    throw new Exception("Error al modificar la respuesta.");
                }
                $modificoAlgunaRespuesta = true;
            }
        }
        return $modificoAlgunaRespuesta;
    }





    // Muestra los datos, se encarga de la visualizacion de las categorias
    public function mostrarFormularioEdicionPregunta()
    {
        try {
            if (isset($_POST['idPregunta'])) {
                $idPregunta = $_POST['idPregunta'];
                $pregunta = $this->model->obtenerPreguntaPorId($idPregunta);  // Obtener la pregunta
                $respuestas = $this->model->obtenerRespuestasDeUnaPregunta($idPregunta);  // Obtener respuestas

                // Marcar la primera respuesta como la correcta
                foreach ($respuestas as $index => $respuesta) {
                    $respuestas[$index]['isFirstResponse'] = ($index == 0);  // La primera respuesta es la correcta
                    $respuestas[$index]['es_correcta'] = ($index == 0);  // Asegurar que la primera es correcta
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
                // Pasar los datos a la vista
                $this->presenter->show('modificarPreguntaReportada', $data);
            }
        } catch (Exception $e) {
            // Manejo de errores
            $this->presenter->show('error', ['mensajeError' => "No se pudo cargar la pregunta: " . $e->getMessage()]);
        }
    }
}