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


    public function sugerirPregunta()
    {
        $categorias = $this->model->traerCategorias();

        // Preparar los datos para el presenter
        $data = [
            'categorias' => $categorias
        ];


        $this->presenter->show('sugerirPregunta',$data);
    }

    // Cuando clickeo enviar sugerencia, valida que los datos enviados por el form sean correctos y crea la pregunta con estado pendiente.
    // Con la variable preguntaEnviada en true muestra un cartel de pregunta enviada correctamente
    public function validarPreguntaSugerida()
    {
        $preguntaSugerida = $_POST['pregunta_sugerida'] ?? '';
        $respuestaCorrecta = $_POST['respuesta_correcta'] ?? '';
        $respuestaIncorrecta1 = $_POST['respuesta_incorrecta_1'] ?? '';
        $respuestaIncorrecta2 = $_POST['respuesta_incorrecta_2'] ?? '';
        $idCategoria = $_POST['idCategoria'] ?? 1; // La categoría default es países
        $estado = 'pendiente';

        // Llamar al método que guarda la pregunta y las respuestas
        $this->model->agregarPreguntaConUnaRespuestaCorrectaYDosIncorrectasYCambiarDeEstado(
            $preguntaSugerida,
            $respuestaCorrecta,
            $respuestaIncorrecta1,
            $respuestaIncorrecta2,
            $estado,
            $idCategoria
        );

        // Pasar el dato a la vista de éxito
        $this->presenter->show('sugerirPregunta', ['preguntaEnviada' => true]);
    }

    // Ver preguntas reportadas, Obtiene las preguntas reportadas junto a su categoria y respuestas y se las manda a la vista
    public function preguntasReportadas()
    {
        try {

            $preguntasReportadas = $this->model->obtenerPreguntasReportadas();

            foreach ($preguntasReportadas as &$pregunta) {
                // Obtener las respuestas de la pregunta
                $respuestas = $this->model->obtenerRespuestasDeUnaPregunta($pregunta['id_pregunta']);
                if (!empty($respuestas)) {
                    $pregunta['respuestas'] = $respuestas;
                }

                $categoria = $this->model->obtenerCategoriaPregunta($pregunta['id_pregunta']);
                if (!empty($categoria)) {
                    $pregunta['categoria'] = $categoria; // Añade la categoría a la pregunta
                } else {
                    $pregunta['categoria'] = 'Categoría no disponible';
                }
            }

            // Pasar las preguntas reportadas con sus datos a la vista
            $this->presenter->show('preguntasReportadas', ['preguntasReportadas' => $preguntasReportadas]);
        } catch (Exception $e) {
            // En caso de error, mostrar mensaje de error
            $this->presenter->show('error', ['mensajeError' => "No se pudieron obtener las preguntas reportadas: " . $e->getMessage()]);
        }
    }


    // Si se da de alta o da de baja, maneja la accion y redirige a la vista de los reportes
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

    // Si se elige modificar la pregunta, se muestra el form
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
                    $respuestas[$index]['isPrimerRespuesta'] = ($index == 0);
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

    // Cuando guardo los cambios a la pregunta reportada, valida y aplica y redirige a preguntas reportadas
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

    // Si elige ver reportes, los muestra relacionados a esa pregunta en especifico (valor pasa por get)

    public function verReportes()
    {
        // Validar que se recibió un parámetro GET
        if (!isset($_GET['idPregunta']) || !is_numeric($_GET['idPregunta']) || intval($_GET['idPregunta']) <= 0) {
            $this->presenter->show("error", ["mensajeError" => "ID de pregunta inválido."]);
            return;
        }

        $idPregunta = intval($_GET['idPregunta']); // Convertir a entero para mayor seguridad

        try {
            // Obtener los reportes de la pregunta
            $reportes = $this->model->obtenerReportesDeUnaPregunta($idPregunta);

            // Renderizar la vista de reportes
            $this->presenter->show("reportesDePregunta", [
                "id_pregunta" => $idPregunta,
                "reportes" => $reportes,
            ]);
        } catch (Exception $e) {
            $this->presenter->show("error", [
                "mensajeError" => "No se pudieron obtener los reportes de la pregunta: " . $e->getMessage()
            ]);
        }
    }



    // Obtiene las preguntas sugeridas y las muestra

    public function preguntasSugeridas()
    {
        try {
            $preguntasReportadas = $this->model->obtenerPreguntasSugeridas();
            foreach ($preguntasReportadas as &$pregunta) {
                $respuestas = $this->model->obtenerRespuestasDeUnaPregunta($pregunta['id_pregunta']);
                if (!empty($respuestas)) {
                    $pregunta['respuestas'] = $respuestas;
                }
            }
            $this->presenter->show('preguntasSugeridas', ['preguntasSugeridas' => $preguntasReportadas]);
        } catch (Exception $e) {
            $this->presenter->show('error', ['mensajeError' => "No se pudieron obtener las preguntas sugeridas: " . $e->getMessage()]);
        }
    }

    // Dependiendo de la accion elegida, aprueba o rechaza la sugerencia
    public function manejoAccionSugerencia() {
        try {
            if (isset($_POST['accion']) && isset($_POST['idPregunta'])) {
                $accion = $_POST['accion'];
                $idPregunta = $_POST['idPregunta'];

                // Validaciones
                $this->validarAccion($accion);
                $this->validarIdPregunta($idPregunta);

                // Lógica según la acción
                $resultado = false;
                if ($accion === 'aprobar') {
                    $resultado = $this->model->aprobarSugerencia($idPregunta);
                } elseif ($accion === 'rechazar') {
                    $resultado = $this->model->rechazarSugerencia($idPregunta);
                } else {
                    throw new Exception("Acción no reconocida.");
                }

                // Verificar el resultado
                if ($resultado) {
                    $this->redirigirAPreguntasSugeridas();
                } else {
                    throw new Exception("Error al procesar la sugerencia.");
                }
            } else {
                throw new Exception("Datos incompletos.");
            }
        } catch (Exception $e) {
            $this->presenter->show('error', ['mensajeError' => $e->getMessage()]);
        }
    }


    // Métodos de validación privados
    private function validarAccion($accion) {
        $accionesPermitidas = ['darDeAlta', 'darDeBaja', 'aprobar', 'rechazar'];

        if (!in_array($accion, $accionesPermitidas)) {
            throw new Exception("Acción no reconocida: $accion");
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

    // Redireccionamientos

    private function redirigirAPreguntasReportadas()
    {
        header("Location: /editor/preguntasReportadas");
        exit();
    }

    private function redirigirAPreguntasSugeridas() {
        header("Location: /editor/preguntasSugeridas");
        exit();
    }


}