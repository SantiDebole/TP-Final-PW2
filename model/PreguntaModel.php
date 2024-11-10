<?php

class PreguntaModel
{
    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function agregarPreguntaConUnaRespuestaCorrectaYDosIncorrectasYCambiarDeEstado(
        $preguntaSugerida,
        $respuestaCorrecta,
        $respuesta_incorrecta_1,
        $respuesta_incorrecta_2,
        $estado
    ){
        // 1. Insertar la pregunta en la tabla `pregunta`
        $sqlPregunta = "INSERT INTO pregunta (descripcion, estado, idCategoria) VALUES (?, ?, null)";
        $stmtPregunta = $this->database->connection->prepare($sqlPregunta);
        $stmtPregunta->bind_param("ss", $preguntaSugerida, $estado);
        $stmtPregunta->execute();

        $idPregunta = $this->database->connection->insert_id;

        // 3. Insertar las respuestas en la tabla `respuesta`
        $sqlRespuesta = "INSERT INTO respuesta (descripcion, esCorrecta, idPregunta) VALUES (?, ?, ?)";
        $stmtRespuesta = $this->database->connection->prepare($sqlRespuesta);

        // Respuesta correcta
        $esCorrecta = 1;
        $stmtRespuesta->bind_param("sii", $respuestaCorrecta, $esCorrecta, $idPregunta);
        $stmtRespuesta->execute();

        // Respuesta incorrecta 1
        $esCorrecta = 0;
        $stmtRespuesta->bind_param("sii", $respuesta_incorrecta_1, $esCorrecta, $idPregunta);
        $stmtRespuesta->execute();

        // Respuesta incorrecta 2
        $stmtRespuesta->bind_param("sii", $respuesta_incorrecta_2, $esCorrecta, $idPregunta);
        $stmtRespuesta->execute();

        // Cerrar las declaraciones
        $stmtPregunta->close();
        $stmtRespuesta->close();
    }


}