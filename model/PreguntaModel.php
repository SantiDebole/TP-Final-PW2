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
        $this->database->executeQueryConParametros($sqlPregunta,[$preguntaSugerida,$estado]);

        $idPregunta = $this->database->connection->insert_id;

        // 3. Insertar las respuestas en la tabla `respuesta`
        $sqlRespuesta = "INSERT INTO respuesta (descripcion, esCorrecta, idPregunta) VALUES (?, ?, ?)";

        // Respuesta correcta
        $esCorrecta = 1;
        $this->database->executeQueryConParametros($sqlRespuesta,[$respuestaCorrecta,$esCorrecta,$idPregunta]);

        // Respuesta incorrecta 1
        $esCorrecta = 0;
        $this->database->executeQueryConParametros($sqlRespuesta,[$respuesta_incorrecta_1,$esCorrecta,$idPregunta]);

        // Respuesta incorrecta 2
        $this->database->executeQueryConParametros($sqlRespuesta,[$respuesta_incorrecta_2,$esCorrecta,$idPregunta]);
    }


}