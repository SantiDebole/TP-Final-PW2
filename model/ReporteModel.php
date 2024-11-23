<?php

class ReporteModel
{
    private $db;

    public function __construct($database)
    {
        $this->db = $database;
    }

    public function traerPuntajeDelUsuarioEnLaPartida($idPartida, $idUsuario) {
        $query = "SELECT SUM(t.puntaje) AS total_puntaje
                  FROM usuario u
                  JOIN partida p ON u.id = p.idUsuario
                  JOIN tienen t ON p.id = t.idPartida
                  JOIN pregunta pr ON t.idPregunta = pr.id
                  WHERE p.id = ? AND u.id = ?
                  GROUP BY u.id";
        $result = $this->db->executeQueryConParametros($query, [$idPartida, $idUsuario]);
        $resultado = $result->fetch_assoc();
        // Retornar el puntaje total o null si no se encuentra el registro
        return $resultado ? $resultado['total_puntaje'] : 0;

    }



    public function reportarPreguntaYEnviarlaALaTablaReporte($idPreguntaReportada, $idUsuarioQueReporta, $textoDelReporte) {
        // Insertar el reporte en la tabla reporte
        $queryReporte = "INSERT INTO reporte (usuario_id, pregunta_id, texto, estado) VALUES (?, ?, ?, 'activo')";
        $this->db->executeQueryConParametros($queryReporte, [$idUsuarioQueReporta, $idPreguntaReportada, $textoDelReporte]);
    }





}