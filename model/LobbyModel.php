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
        $result = $this->database->executeQueryConParametros($query,[$idUsuario]);


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
        $result = $this->database->executeQueryConParametros($query,[$idUsuario]);
        return $result->fetch_assoc(); // Retorna solo una fila con el puntaje más alto
    }

    public function buscarDatosDeOtrosJugadores($idBuscado)
    {
        $usuario = $this->buscarUsuario($idBuscado);


        if (is_null($usuario)){
            return $usuario;
        }else{
            $resultado = $this->traerMisPartidas($usuario['id']);
            $usuario['partidas'] = $resultado['partidas'];
            $usuario['mejor_partida'] = $resultado['mejor_partida'];
            $usuario['puntaje_total'] = $resultado['puntaje_total'];
            return $usuario;
        }




    }


    private function buscarUsuario($idUsuario)
    {
        $query = "SELECT id, usuario, nombre_completo, fecha_nacimiento, genero, email, pais, ciudad, fecha_creacion, foto_perfil FROM usuario where usuario = ?";
        $result = $this->database->executeQueryConParametros($query,[$idUsuario]);

        return $result->fetch_assoc();
    }
       /* private function ejecucionDeConsultaConFetch_assocConUnParametro($query, $param, $variable){
        $stmt = $this->database->connection->prepare($query);
        $stmt->bind_param($param, $variable);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }*/


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
        $result = $this->database->executeQuery($query);
        return $result->fetch_all(MYSQLI_ASSOC);
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
        $result = $this->database->executeQuery($query);
       return $result->fetch_all(MYSQLI_ASSOC);
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
        $result = $this->database->executeQuery($query);
       return $result->fetch_all(MYSQLI_ASSOC);
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
        $result = $this->database->executeQuery($query);
       return $result->fetch_all(MYSQLI_ASSOC);
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

        $result = $this->database->executeQuery($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }


    public function getPosicionRankingMejoresJugadores($userNombre) {
        $query = "WITH PartidasOrdenadas AS (
    SELECT
        usuario.usuario AS usuario,
        partida.id AS id_partida,
        partida.fecha AS fecha_partida,
        partida.estado AS estado_partida,
        ROW_NUMBER() OVER (PARTITION BY usuario.id ORDER BY partida.fecha DESC) AS rn
    FROM partida
    JOIN usuario ON partida.idUsuario = usuario.id
),
RankingGeneral AS (
    SELECT
        usuario,
        AVG(puntaje_total) AS promedio_respuestas,
        ROW_NUMBER() OVER (ORDER BY AVG(puntaje_total) DESC) AS puesto_ranking
    FROM (
        SELECT
            usuario,
            SUM(tienen.puntaje) AS puntaje_total
        FROM PartidasOrdenadas
        JOIN tienen ON tienen.idPartida = id_partida
        WHERE rn <= 25
        GROUP BY usuario, id_partida
        HAVING COUNT(id_partida) > 2
    ) AS subconsulta
    GROUP BY usuario
)
SELECT
    usuario,
    promedio_respuestas,
    puesto_ranking
FROM RankingGeneral
WHERE usuario = ?;
    ";

        // Preparar y ejecutar la consulta usando el parámetro userId
        $result = $this->database->executeQueryConParametros($query,[$userNombre]);
        // Obtener el resultado como un arreglo asociativo
        $resultado = $result->fetch_assoc();

        // Verificar si el resultado contiene datos
        if ($resultado) {
            return $resultado; // Retorna el resultado, que incluirá el puesto en el ranking y el promedio
        } else {
            // Si no se encuentra ningún resultado, puedes devolver null o un mensaje
            return null;
        }
    }


    /*private function ejecucionDeConsultaFetchAllSinParametros($query)
    {

        try {

            $stmt = $this->database->connection->prepare($query);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result->fetch_all(MYSQLI_ASSOC);
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }*/


//Las primeras 10 preguntas son rdm y no dependen del nivel.

// promedio < a 2 les damos las de 70% acetadas para arriba (malos)
// promedio > a 2 y < a 4 (intermedio)
// promedio > a 4 (experto)






}