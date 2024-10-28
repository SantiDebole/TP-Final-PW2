<?php
require_once '../data/db.php';

class PreguntasController
{
    public function crearPregunta($request) {
        global $pdo;

        $pregunta = $request['pregunta'];
        $respuestas = $request['respuestas'];

        if ($pregunta && count($respuestas) > 0) {
            // Insertar la pregunta
            $sqlPregunta = "INSERT INTO pregunta (descripcion, estado) VALUES (?, 'activa')";
            $stmtPregunta = $pdo->prepare($sqlPregunta);
            $stmtPregunta->execute([$pregunta]);
            $idPregunta = $pdo->lastInsertId(); // Obtener el ID de la pregunta insertada

            // Insertar las respuestas
            $sqlRespuesta = "INSERT INTO respuesta (descripcion, esCorrecta, idPregunta) VALUES (?, ?, ?)";
            $stmtRespuesta = $pdo->prepare($sqlRespuesta);

            foreach ($respuestas as $respuesta) {
                $stmtRespuesta->execute([$respuesta['descripcion'], $respuesta['esCorrecta'], $idPregunta]);
            }

            echo json_encode(["message" => "Pregunta y respuestas enviadas con éxito"]);
        } else {
            echo json_encode(["error" => "Por favor, completa todos los campos."]);
        }
    }

}
?>
