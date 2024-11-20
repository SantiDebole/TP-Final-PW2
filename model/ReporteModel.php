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



    public function reportarPreguntaYEnviarlaALaTablaReporte($idPreguntaReportada, $idUsuarioQueReporta, $textoDelReporte) {
        // Insertar el reporte en la tabla reporte
        $queryReporte = "INSERT INTO reporte (usuario_id, pregunta_id, texto, estado) VALUES (?, ?, ?, 'activo')";
        $stmtReporte = $this->db->connection->prepare($queryReporte);
        $stmtReporte->bind_param("iis", $idUsuarioQueReporta, $idPreguntaReportada, $textoDelReporte);
        $stmtReporte->execute();
        $stmtReporte->close();
    }





}