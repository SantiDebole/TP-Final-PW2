<?php

class ReporteModel
{
    private $db;

    public function __construct($database)
    {
        $this->db = $database;
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

    public function verificarPuntajeDeLaUltimaPreguntaConIdPartidaIdUsuarioYIdPregunta($idPartida,$idUsuario,$idPregunta) {
        $query = 'SELECT p.*, t.puntaje
                    FROM pregunta p
                    JOIN tienen t ON p.id = t.idPregunta
                    JOIN partida pa ON t.idPartida = pa.id
                    JOIN usuario u ON pa.idUsuario = u.id
                    WHERE u.id = ? AND pa.id = ? AND p.id =?
                    ORDER BY pa.fecha DESC, t.idPregunta DESC
                    LIMIT 1;
';
        $stmt = $this->db->connection->prepare($query);
        $stmt->bind_param("iii",$idUsuario, $idPartida, $idPregunta);
        $stmt->execute();
        $resultado = $stmt->get_result()->fetch_assoc();

        if($resultado['puntaje']==0){
            return "respuesta incorrecta";
        }else{
            return "respuesta correcta";
        }

    }




}