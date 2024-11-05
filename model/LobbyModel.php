<?php

class LobbyModel
{
    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function traerMisPartidas($idUsuario)
    {
        $query = "SELECT partida.id AS id_partida, 
               partida.fecha AS fecha_partida, 
               partida.estado AS estado_partida, 
               SUM(tienen.puntaje) AS puntaje_total
              FROM partida
              JOIN tienen ON partida.id = tienen.idPartida
              WHERE partida.idUsuario = ?
              GROUP BY partida.id, partida.fecha, partida.estado;";

        $stmt = $this->database->connection->prepare($query);
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

    public function traerPartidaConMayorPuntaje($idUsuario)
    {
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

        $stmt = $this->database->connection->prepare($query);
        $stmt->bind_param("i", $idUsuario);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc(); // Retorna solo una fila con el puntaje más alto
    }

    public function buscarDatosDeOtrosJugadores($idBuscado)
    {
        $usuario = $this->buscarUsuario($idBuscado);
        if (is_null($usuario)) return $usuario;
        $resultado = $this->traerMisPartidas($usuario['id']);
        $usuario['partidas'] = $resultado['partidas'];
        $usuario['mejor_partida'] = $resultado['mejor_partida'];
        $usuario['puntaje_total'] = $resultado['puntaje_total'];
        return $usuario;


    }


    private function buscarUsuario($idUsuario)
    {
        $query = "SELECT id, usuario, nombre_completo, fecha_nacimiento, genero, email, pais, ciudad, fecha_creacion FROM usuario where usuario = ?";
        $stmt = $this->database->connection->prepare($query);
        $stmt->bind_param("s", $idUsuario);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function obtenerRanking()
    {
        $resultado['topPuntosTotales'] = $this->obtenerTop10PorPuntosTotales();
        $resultado['topPartidasHistorico'] = $this->obtenerPartidasHistorico();
        $resultado['topPartidasDelMes'] = $this->obtenerPartidasDelMes();
        $resultado['topPartidasDeLaSemana'] = $this->obtenerPartidasDeLaSemana();
        $resultado['top10MejoresJugadores'] = $this->obtenerMejoresJugadores();
        return $resultado;
    }

    private function obtenerTop10PorPuntosTotales()
    {
        $query = "SELECT usuario.usuario as usuario,
                  SUM(tienen.puntaje) AS puntaje_total
                  FROM partida
                  JOIN tienen ON partida.id = tienen.idPartida
                  JOIN	usuario on partida.idUsuario = usuario.id
                  GROUP BY usuario.id
                  ORDER BY puntaje_total DESC
                  LIMIT 10;";
        return $this->ejecucionDeConsultaFetchAllSinParametros($query);
    }
    private function obtenerPartidasHistorico()
    {
        $query = "SELECT
                    usuario.usuario as usuario,
                    partida.id AS id_partida, 
                    partida.fecha AS fecha_partida, 
                    partida.estado AS estado_partida, 
                    SUM(tienen.puntaje) AS puntaje_total
                    FROM partida
                    JOIN tienen ON partida.id = tienen.idPartida
                    JOIN usuario on partida.idUsuario = usuario.id
                    GROUP BY partida.id, partida.fecha, partida.estado
                    ORDER BY puntaje_total DESC
                    LIMIT 10;";
       return $this->ejecucionDeConsultaFetchAllSinParametros($query);
    }
    private function obtenerPartidasDelMes()
    {
        $query = "SELECT
                        usuario.usuario AS usuario,
                        partida.id AS id_partida, 
                        partida.fecha AS fecha_partida, 
                        partida.estado AS estado_partida, 
                        SUM(tienen.puntaje) AS puntaje_total
                        FROM partida
                        JOIN tienen ON partida.id = tienen.idPartida
                        JOIN usuario ON partida.idUsuario = usuario.id
                        WHERE partida.fecha >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)
                        GROUP BY partida.id, partida.fecha, partida.estado
                        ORDER BY puntaje_total DESC
                        LIMIT 10;";
       return $this->ejecucionDeConsultaFetchAllSinParametros($query);
    }
    private function obtenerPartidasDeLaSemana()
    {
        $query = "SELECT
                        usuario.usuario AS usuario,
                        partida.id AS id_partida, 
                        partida.fecha AS fecha_partida, 
                        partida.estado AS estado_partida, 
                        SUM(tienen.puntaje) AS puntaje_total
                        FROM partida
                        JOIN tienen ON partida.id = tienen.idPartida
                        JOIN usuario ON partida.idUsuario = usuario.id
                        WHERE partida.fecha >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)
                        GROUP BY partida.id, partida.fecha, partida.estado
                        ORDER BY puntaje_total DESC
                        LIMIT 10;";

       return $this->ejecucionDeConsultaFetchAllSinParametros($query);
    }
    private function obtenerMejoresJugadores()
    {
        $query = "WITH PartidasOrdenadas AS (
                                            SELECT
                                            usuario.usuario AS usuario,          
                                            partida.id AS id_partida,            
                                            partida.fecha AS fecha_partida,      
                                            partida.estado AS estado_partida,     
                                            ROW_NUMBER() OVER (PARTITION BY usuario.id ORDER BY partida.fecha DESC) AS rn
                                            FROM partida
                                            JOIN usuario ON partida.idUsuario = usuario.id
                                            )
                                            
                    SELECT
                    usuario,
                    AVG(puntaje_total) as promedio_respuestas
                    from(
                    SELECT
                    usuario,
                    sum(tienen.puntaje) AS puntaje_total
                    FROM PartidasOrdenadas
                    JOIN tienen on tienen.idPartida = id_partida
                    WHERE rn <= 25  -- el numero de partidas maximas que vamos a contabilizar
                    GROUP BY usuario, id_partida
                    HAVING COUNT(id_partida) > 2 -- Numero minimo de partidas
                    ) as subconsulta
                    GROUP BY usuario
                    ORDER by promedio_respuestas DESC;";


        return $this->ejecucionDeConsultaFetchAllSinParametros($query);
    }

    private function ejecucionDeConsultaFetchAllSinParametros($query){
    $stmt = $this->database->connection->prepare($query);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}



}