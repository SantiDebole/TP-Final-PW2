<?php

class LobbyModel {
    private $db;

    public function __construct($db){
        $this->db = $db;
    }

    public function traerMisPartidas($idUsuario) {
        $query = "SELECT partida.id AS id_partida, 
               partida.fecha AS fecha_partida, 
               partida.estado AS estado_partida, 
               SUM(tienen.puntaje) AS puntaje_total
              FROM partida
              JOIN tienen ON partida.id = tienen.idPartida
              WHERE partida.idUsuario = ?
              GROUP BY partida.id, partida.fecha, partida.estado;";

        $stmt = $this->db->connection->prepare($query);
        $stmt->bind_param("i", $idUsuario);
        $stmt->execute();
        $result = $stmt->get_result();



        $partidas = [];
        while ($row = $result->fetch_assoc()) {
            $partidas[] = $row;
        }

        $partidaMejorPuntaje = $this->traerPartidaConMayorPuntaje($idUsuario);

        return [
            'partidas' => $partidas,
            'mejor_partida' => $partidaMejorPuntaje
        ];
    }

    public function traerPartidaConMayorPuntaje($idUsuario) {
        $query = "SELECT partida.id AS id_partida, 
                     partida.fecha AS fecha_partida, 
                     partida.estado AS estado_partida, 
                     SUM(tienen.puntaje) AS puntaje_total
              FROM partida
              JOIN tienen ON partida.id = tienen.idPartida
              WHERE partida.idUsuario = ?
              GROUP BY partida.id, partida.fecha, partida.estado
              ORDER BY puntaje_total DESC
              LIMIT 1;";

        $stmt = $this->db->connection->prepare($query);
        $stmt->bind_param("i", $idUsuario);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc(); // Retorna solo una fila con el puntaje más alto
    }




}