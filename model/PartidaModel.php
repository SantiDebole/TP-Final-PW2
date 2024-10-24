<?php

class PartidaModel
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getPregunta(){
        $idPregunta = random_int(1,1);
        $query = "SELECT p.id as idPregunta, p.descripcion as pregunta, r.descripcion as respuesta, r.id as idRta
        FROM pregunta p join respuesta r on p.id = r.idPregunta WHERE p.id = ?";
        $stmt = $this->db->connection->prepare($query);
        $stmt->bind_param("i", $idPregunta);
        $stmt->execute();
        $result = $stmt->get_result();

        $data = [
            "idPregunta" => "",
            "pregunta" => "",
            "respuestas" => []
        ];

        while ($row = $result->fetch_assoc()) {
            if (empty($data["idPregunta"])){
                $data["idPregunta"] = $row["idPregunta"];
            }
            if (empty($data["pregunta"])) {
                $data["pregunta"] = $row["pregunta"];
            }
            $data["respuestas"][] = [
                "respuesta" => $row["respuesta"],
                "idRta" => $row["idRta"]
            ];
        }

        return $data;
    }
}