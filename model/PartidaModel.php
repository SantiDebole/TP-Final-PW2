<?php
//$_SESSION, $_GET, $_POST, $_FILES
class PartidaModel
{
    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function crearPartida($idUsuario) {

        // El estado inicial de la partida
        $estado = 'activa';

        // Preparar la consulta para insertar una nueva partida
        // NOTA: No necesitamos especificar la columna de fecha porque se autocompletará con CURRENT_TIMESTAMP
        $stmt = $this->database->connection->prepare("
        INSERT INTO partida (estado, idUsuario) 
        VALUES (?, ?)
    ");

        // Vincular los parámetros (estado es una cadena y idUsuario es un entero)
        $stmt->bind_param("si", $estado, $idUsuario);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            // Obtener el ID de la partida recién creada
            $partidaId = $stmt->insert_id;

            // Cerrar la consulta
            $stmt->close();

            // Devolver el ID de la nueva partida
            return $partidaId;
        } else {
            // Manejar el error si la inserción falla
            $stmt->close();
            return false;
        }
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