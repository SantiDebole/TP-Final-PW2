<?php

class AdministradorModel
{
    private $db;

    public function __construct($database)
    {
        $this->db = $database;
    }


    public function verCantidadJugadores($rango)
    {
        $query = "SELECT COUNT(*) AS cantidad_jugadores 
              FROM usuario 
              WHERE rol = 'ur' AND $rango";
        $stmt = $this->db->connection->prepare($query);
        $stmt->execute();
        $resultado = $stmt->get_result()->fetch_assoc();
        return $resultado ? $resultado['cantidad_jugadores'] : 0;
    }

    public function verCantidadPartidasJugadas($rango)
    {
        $query = "SELECT COUNT(*) AS cantidad_partidas 
              FROM partida 
              WHERE $rango";
        $stmt = $this->db->connection->prepare($query);
        $stmt->execute();
        $resultado = $stmt->get_result()->fetch_assoc();

        return $resultado ? $resultado['cantidad_partidas'] : 0;
    }

    public function verCantidadPreguntasEnElJuego($rango){

        $query = "SELECT COUNT(*) AS cantidad_preguntas FROM pregunta;";
        $stmt = $this->db->connection->prepare($query);
        $stmt->execute();
        $resultado = $stmt->get_result()->fetch_assoc();

        return $resultado ? $resultado['cantidad_preguntas'] : 0;

    }

    public function verCantidadPreguntasRespondidasCorrectamenteEnElJuego($rango){
        $query = "SELECT COUNT(*) AS cantidad_preguntas_respondidas_correctamente 
                    FROM tienen
                    WHERE puntaje = 1 AND $rango";
        $stmt = $this->db->connection->prepare($query);
        $stmt-> execute();
        $resultado = $stmt->get_result()->fetch_assoc();

        return $resultado ? $resultado['cantidad_preguntas_respondidas_correctamente'] : 0;
    }

    public function verCantidadPreguntasHechasEnElJuego($rango){

        $query = "SELECT COUNT(*) AS cantidad_preguntas_hechas 
                    FROM tienen
                    WHERE $rango";
        $stmt = $this->db->connection->prepare($query);
        $stmt-> execute();
        $resultado = $stmt->get_result()->fetch_assoc();

        return $resultado ? $resultado['cantidad_preguntas_hechas'] : 0;

    }

}