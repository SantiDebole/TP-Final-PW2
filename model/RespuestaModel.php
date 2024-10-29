<?php


class RespuestaModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Método para crear una respuesta
    public function crearRespuesta($descripcion, $esCorrecta, $idPregunta) {
        $query = "INSERT INTO respuesta (descripcion, esCorrecta, idPregunta) VALUES (?, ?, ?)";
        $stmt = $this->db->connection->prepare($query);
        $stmt->bind_param("sii", $descripcion, $esCorrecta, $idPregunta);
        return $stmt->execute();
    }

    // Método para obtener respuestas de una pregunta específica
    public function obtenerRespuestasPorPregunta($idPregunta) {
        $query = "SELECT id, descripcion, esCorrecta FROM respuesta WHERE idPregunta = ?";
        $stmt = $this->db->connection->prepare($query);
        $stmt->bind_param("i", $idPregunta);
        $stmt->execute();
        $result = $stmt->get_result();

        $respuestas = [];
        while ($row = $result->fetch_assoc()) {
            $respuestas[] = $row;
        }
        return $respuestas;
    }
}
?>
