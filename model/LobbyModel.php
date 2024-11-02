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


        $puntajeTotal = 0;
        $partidas = [];
        while ($row = $result->fetch_assoc()) {
            $partidas[] = $row;
            $puntajeTotal += $row['puntaje_total'];
        }

        $partidaMejorPuntaje = $this->traerPartidaConMayorPuntaje($idUsuario);

        return [
            'partidas' => $partidas,
            'mejor_partida' => $partidaMejorPuntaje,
            'puntaje_total' => $puntajeTotal
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

    public function buscarDatosDeOtrosJugadores($idBuscado){
        $usuario = $this->buscarUsuario($idBuscado);
        if(is_null($usuario)) return $usuario;
        $resultado= $this->traerMisPartidas($usuario['id']);
        $usuario['partidas'] = $resultado['partidas'];
        $usuario['mejor_partida'] = $resultado['mejor_partida'];
        $usuario['puntaje_total'] = $resultado['puntaje_total'];
        return $usuario;


    }


    private function buscarUsuario($idUsuario)
    {
        $query = "SELECT id, usuario, nombre_completo, fecha_nacimiento, genero, email, pais, ciudad, fecha_creacion FROM usuario where usuario = ?";
        $stmt = $this->db->connection->prepare($query);
        $stmt->bind_param("s", $idUsuario);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }


}