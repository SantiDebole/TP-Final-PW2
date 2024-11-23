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
        // Preparar la consulta SQL
        $query = "SELECT COUNT(*) AS cantidad_jugadores 
              FROM usuario 
              WHERE rol = 'ur' AND $rango";

        // Preparar la sentencia
        $stmt = $this->db->connection->prepare($query);

        // Ejecutar la consulta
        $stmt->execute();

        // Obtener el resultado
        $resultado = $stmt->get_result()->fetch_assoc();

        // Retornar la cantidad de jugadores o 0 si no se encuentra el registro
        return $resultado ? $resultado['cantidad_jugadores'] : 0;
    }

    public function verCantidadPartidasJugadas($rango)
    {
        // Preparar la consulta SQL
        $query = "SELECT COUNT(*) AS cantidad_partidas 
              FROM partida 
              WHERE $rango";

        // Preparar la sentencia
        $stmt = $this->db->connection->prepare($query);

        // Ejecutar la consulta
        $stmt->execute();

        // Obtener el resultado
        $resultado = $stmt->get_result()->fetch_assoc();

        // Retornar la cantidad de jugadores o 0 si no se encuentra el registro
        return $resultado ? $resultado['cantidad_partidas'] : 0;
    }

    public function verCantidadPreguntasEnElJuego($rango){

        $query = "SELECT COUNT(*) AS cantidad_preguntas FROM pregunta;
";

        // Preparar la sentencia
        $stmt = $this->db->connection->prepare($query);

        // Ejecutar la consulta
        $stmt->execute();

        // Obtener el resultado
        $resultado = $stmt->get_result()->fetch_assoc();

        // Retornar la cantidad de jugadores o 0 si no se encuentra el registro
        return $resultado ? $resultado['cantidad_preguntas'] : 0;

    }

}