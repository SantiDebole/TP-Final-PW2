<?php

class PartidaModel
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function traerPuntajeDelUsuarioEnLaPartida($idPartida, $idUsuario) {
        // Preparar la consulta SQL
        $query = "SELECT SUM(t.puntaje) AS total_puntaje
                  FROM usuario u
                  JOIN partida p ON u.id = p.idUsuario
                  JOIN tienen t ON p.id = t.idPartida
                  JOIN pregunta pr ON t.idPregunta = pr.id
                  WHERE p.id = ? AND u.id = ?
                  GROUP BY u.id";

        // Preparar la sentencia
        $stmt = $this->db->connection->prepare($query);

        // Vincular los parámetros
        $stmt->bind_param("ii", $idPartida, $idUsuario);

        // Ejecutar la consulta
        $stmt->execute();

        // Obtener el resultado
        $resultado = $stmt->get_result()->fetch_assoc();



        // Retornar el puntaje total o null si no se encuentra el registro
        return $resultado ? $resultado['total_puntaje'] : 0;

    }


    public function desactivarPartida($idPartida){
        $query = "UPDATE partida SET estado = 'inactivo' WHERE id = ?";
        $stmt = $this->db->connection->prepare($query);
        $stmt->bind_param("i",$idPartida);
        $stmt->execute();
    }

    public function crearPartida($idUsuario){
        $fecha = date('Y-m-d H:i:s');
        $estado = "Activo";
        $query = "INSERT INTO partida(fecha, estado,idUsuario) VALUES (?,?,?)";
        $stmt = $this->db->connection->prepare($query);
        $stmt->bind_param("ssi", $fecha, $estado, $idUsuario);
        $stmt->execute();
    }

    public function getPartida($idUsuario){
        $query = "SELECT id FROM partida WHERE idUsuario = ? AND estado = 'activo'";
        $stmt = $this->db->connection->prepare($query);
        $stmt->bind_param("i", $idUsuario);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function getPregunta(){
        //obtiene una pregunta random de entre todas las que hay pero de momento no lo hace como tal ya que esta
        //hardcodeado
        //buscar en tabla tienen las preguntas que todavia no haya respondido
        $idPregunta = random_int(1, 10);
        $query = "SELECT p.id as idPregunta, p.descripcion as pregunta, r.descripcion as respuesta, r.id as idRta
              FROM pregunta p 
              JOIN respuesta r ON p.id = r.idPregunta 
              WHERE p.id = ?";

        $stmt = $this->db->connection->prepare($query);
        $stmt->bind_param("i", $idPregunta);
        $stmt->execute();
        $result = $stmt->get_result();

        $data = [
            "pregunta" => [
                "idPregunta" => null,
                "descripcion" => ""
            ],
            "respuestas" => []
        ];

        while ($row = $result->fetch_assoc()) {
            if ($data["pregunta"]["idPregunta"] === null) {
                $data["pregunta"]["idPregunta"] = $row["idPregunta"];
                $data["pregunta"]["descripcion"] = $row["pregunta"];
            }

            $data["respuestas"][] = [
                "respuesta" => $row["respuesta"],
                "idRta" => $row["idRta"]
            ];
        }

        return $data;
    }


    public function validarRespuesta($idRespuesta){
        $query = "SELECT * FROM respuesta WHERE id = ?";
        $stmt = $this->db->connection->prepare($query);
        $stmt->bind_param("i", $idRespuesta);
        $stmt->execute();
        $result = $stmt->get_result();
        if($row = $result->fetch_assoc()){
            if($row["esCorrecta"] == 1){
                return true;
            }
        }
        return false;
    }

    public function registrarPreguntaCorrecta($idPartida, $idPregunta){
        $query = "INSERT INTO tienen (idPartida, idPregunta,puntaje) VALUES (?,?,?)";
        $puntaje = 1;
        $stmt = $this->db->connection->prepare($query);
        $stmt->bind_param("iii", $idPartida, $idPregunta,$puntaje);
        $stmt->execute();
    }
    public function registrarPreguntaIncorrecta($idPartida, $idPregunta){
        $query = "INSERT INTO tienen (idPartida, idPregunta,puntaje) VALUES (?,?,?)";
        $puntaje = 0;
        $stmt = $this->db->connection->prepare($query);
        $stmt->bind_param("iii", $idPartida, $idPregunta,$puntaje);
        $stmt->execute();
    }
}