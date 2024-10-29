<?php


class PreguntaModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Método para crear una pregunta con sus respuestas
    public function crearPregunta($descripcion, $estado, $idCategoria, $respuestas) {
        $query = "INSERT INTO pregunta (descripcion, estado, idCategoria) VALUES (?, ?, ?)";
        $stmt = $this->db->connection->prepare($query);
        $stmt->bind_param("ssi", $descripcion, $estado, $idCategoria);

        if ($stmt->execute()) {
            $idPregunta = $this->db->connection->insert_id;

            // Crear las respuestas asociadas
            $respuestaModel = new RespuestaModel($this->db);
            foreach ($respuestas as $respuesta) {
                $respuestaModel->crearRespuesta($respuesta['descripcion'], $respuesta['esCorrecta'], $idPregunta);
            }
            return true;
        }
        return false;
    }

    // Método para obtener una pregunta con sus respuestas
    public function obtenerPreguntaConRespuestas($idPregunta) {
        $query = "SELECT descripcion, estado, idCategoria FROM pregunta WHERE id = ?";
        $stmt = $this->db->connection->prepare($query);
        $stmt->bind_param("i", $idPregunta);
        $stmt->execute();
        $result = $stmt->get_result();
        $pregunta = $result->fetch_assoc();

        // Obtener respuestas de la pregunta
        if ($pregunta) {
            $respuestaModel = new RespuestaModel($this->db);
            $pregunta['respuestas'] = $respuestaModel->obtenerRespuestasPorPregunta($idPregunta);
        }
        return $pregunta;
    }
}
?>
