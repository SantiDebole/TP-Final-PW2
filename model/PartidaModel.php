<?php

class PartidaModel
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
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
        $query = "SELECT id FROM partida WHERE idUsuario = ?";
        $stmt = $this->db->connection->prepare($query);
        $stmt->bind_param("i", $idUsuario);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function getPregunta(){
        //obtiene una pregunta random de entre todas las que hay pero de momento no lo hace como tal
        $idPregunta = random_int(1, 1);
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

    public function registrarPuntaje($idPartida, $idPregunta){
        $query = "INSERT INTO tienen (idPartida, idPregunta,puntaje) VALUES (?,?,?)";
        $puntaje = 1;
        $stmt = $this->db->connection->prepare($query);
        $stmt->bind_param("iii", $idPartida, $idPregunta,$puntaje);
        $stmt->execute();
    }
}