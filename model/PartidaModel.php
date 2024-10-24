<?php
//$_SESSION, $_GET, $_POST, $_FILES
class PartidaModel
{
    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function traerPregunta() {
        $stmt = $this->database->connection->prepare("SELECT id, descripcion FROM pregunta LIMIT 1");
        $stmt->execute();
        $stmt->bind_result($id, $descripcion);
        $stmt->fetch();

        return [
            'id' => $id,
            'descripcion' => $descripcion
        ];
    }

    public function traerRespuestas($idPregunta) {
        $stmt = $this->database->connection->prepare("SELECT descripcion, esCorrecta FROM respuesta WHERE idPregunta = ?");
        $stmt->bind_param("i", $idPregunta); // "i" indica que es un entero
        $stmt->execute();
        $stmt->bind_result($descripcion, $esCorrecta);

        $respuestas = [];
        while ($stmt->fetch()) {
            $respuestas[] = [
                'descripcion' => $descripcion,
                'esCorrecta' => $esCorrecta
            ];
        }

        return $respuestas;
    }

    public function verificarRespuesta($idPregunta, $respuestaSeleccionada) {
        // Consulta para verificar si la respuesta seleccionada es correcta
        $stmt = $this->database->connection->prepare("
        SELECT esCorrecta 
        FROM respuesta 
        WHERE idPregunta = ? AND descripcion = ?
    ");
        $esCorrecta = null;

        // Vinculamos los parámetros
        $stmt->bind_param("is", $idPregunta, $respuestaSeleccionada);

        // Ejecutamos la consulta
        $stmt->execute();

        // Obtenemos el resultado
        $stmt->bind_result($esCorrecta);
        $stmt->fetch();

        // Cerramos la consulta
        $stmt->close();

        // Retornamos si es correcta (1) o incorrecta (0)
        return $esCorrecta == 1;
    }






}