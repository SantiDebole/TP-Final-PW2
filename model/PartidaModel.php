<?php
//$_SESSION, $_GET, $_POST, $_FILES
class PartidaModel
{
    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function traerPreguntaConRespuestas(){

        $sql = "SELECT 
                        p.id AS idPregunta,
                        p.descripcion AS descripcionPregunta,
                        p.estado AS estadoPregunta,
                        r.id AS idRespuesta,
                        r.descripcion AS descripcionRespuesta,
                        r.esCorrecta
                    FROM 
                        pregunta p
                    JOIN 
                        respuesta r ON p.id = r.idPregunta
                    ORDER BY 
                        p.id, r.id;
                    ";

        // Preparar la consulta
        $stmt = $this->database->connection->prepare($sql);

    }



}