<?php

class PartidaModel
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }


    public function getPreguntaConRespuestas($idUsuario){
        $nivel = $this->calcularNivelDelUsuario($idUsuario);
        $preguntasNoVistasPorUsuario = $this->getPreguntasNoVistasPorUsuario($idUsuario,$nivel);
        if($preguntasNoVistasPorUsuario){
            $idPregunta = $this->getPreguntaAleatoria($preguntasNoVistasPorUsuario);

            return $this->obtenerRespuestasALaPregunta($idPregunta);
        }else{
            $this->reiniciarRegistroDePreguntasVistasPorUsuario($idUsuario);
            $this->getPreguntaConRespuestas($idUsuario);
        }

    }

    public function crearPartida($idUsuario)
    {

        $fecha = date('Y-m-d H:i:s');
        $estado = "Activo";
        $query = "INSERT INTO partida(fecha, estado,idUsuario) VALUES (?,?,?)";
        $stmt = $this->db->connection->prepare($query);
        $stmt->bind_param("ssi", $fecha, $estado, $idUsuario);
        $stmt->execute();
        return $this->db->connection->insert_id;


    }

    public function consultarPartidaDisponible($idUsuario)
    {
        var_dump($idUsuario);
        $partidaDisponible = $this->tienePartidaDisponible($idUsuario);
        if ($partidaDisponible) {
            $pregunta = $this->tienePreguntaDisponible($partidaDisponible['id']);
            if ($pregunta) {
                if ($this->controladorDeExpiracionDePregunta($pregunta['fecha'])) {
                    return true;
                } else {
                    $this->marcarPreguntaComoIncorrecta($pregunta);
                    $this->marcarPartidaComoFinalizada($partidaDisponible);
                    return false;
                }
            } else return true;
        }
        return false;

    }

    public function terminarPartida($idUsuario)
    {
        $partidaDisponible = $this->tienePartidaDisponible($idUsuario);
        $pregunta = $this->tienePreguntaDisponible($partidaDisponible['id']);
        $this->marcarPreguntaComoIncorrecta($pregunta);
        $this->marcarPartidaComoFinalizada($partidaDisponible);
    }

    private function marcarPartidaComoFinalizada($partidaDisponible)
    {
        $query = "update partida
        set estado = 'inactivo'
        where id = ?";
        $stmt = $this->db->connection->prepare($query);
        $stmt->bind_param("i", $partidaDisponible['id']);
        $stmt->execute();

    }

    private function marcarPreguntaComoIncorrecta($pregunta)
    {
        $query = "update tienen
        set puntaje = 0
        where idPartida = ? and idPregunta = ? and fecha= ?";
        $stmt = $this->db->connection->prepare($query);
        $stmt->bind_param("iis", $pregunta['idPartida'], $pregunta['idPregunta'], $pregunta['fecha']);
        $stmt->execute();
    }

    private function controladorDeExpiracionDePregunta($horario)
    {
        $fechaPregunta = new DateTime($horario);
        $fechaActual = new DateTime();
        // var_dump($fechaActual);
        $intervalo = $fechaPregunta->diff($fechaActual); //Diff devuelve un DateInterval
        if ($intervalo->i < 1 && $intervalo->h == 0 && $intervalo->d == 0 && $intervalo->m == 0 && $intervalo->y == 0) return true;
        return false;
    }

    private function tienePartidaDisponible($idUsuario)
    {
        $query = "SELECT id 
        from partida
        where idUsuario = ?
        and estado = 'activo'";
        $stmt = $this->db->connection->prepare($query);
        $stmt->bind_param("i", $idUsuario);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();

        return $resultado;
    }

    private function tienePreguntaDisponible($idPartida)
    {
        $query = "SELECT idPartida, idPregunta, fecha
        from tienen
        where idPartida = ?
        and puntaje IS NULL";
        $stmt = $this->db->connection->prepare($query);
        $stmt->bind_param("i", $idPartida);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    private function filtrarPreguntasPorNivelDeUsuario($nivel): string
    {
        $dificultad = "";
        if ($nivel === "alto") {
            $dificultad = '
                WITH PreguntasFiltradasPorNivel AS (
                    SELECT tienen.idPregunta AS idPreguntaFiltrada,
                           AVG(tienen.puntaje) AS promedio
                    FROM tienen
                    GROUP BY tienen.idPregunta
                    HAVING promedio < 0.3
                )';
        } elseif ($nivel === "medio") {
            $dificultad = '
                WITH PreguntasFiltradasPorNivel AS (
                    SELECT tienen.idPregunta AS idPreguntaFiltrada,
                           AVG(tienen.puntaje) AS promedio
                    FROM tienen
                    GROUP BY tienen.idPregunta
                    HAVING promedio > 0.3 AND promedio < 0.7
                )';
        } else {
            $dificultad = '
                WITH PreguntasFiltradasPorNivel AS (
                    SELECT tienen.idPregunta AS idPreguntaFiltrada,
                           AVG(tienen.puntaje) AS promedio
                    FROM tienen
                    GROUP BY tienen.idPregunta
                    HAVING promedio > 0.7
                )';
        }
        return $dificultad;
    }
    private function calcularNivelDelUsuario($idUsuario)
    {
        $query = "SELECT AVG(tienen.puntaje) AS promedioDeAciertoDelUsuario
                    FROM tienen join partida on tienen.idPartida = partida.id
                    where partida.idUsuario = ?
                    GROUP BY partida.idUsuario";
        $stmt = $this->db->connection->prepare($query);
        $stmt->bind_param("i", $idUsuario);
        $stmt->execute();
        $result = $stmt->get_result();
        $result = $result->fetch_assoc();
        if($result['promedioDeAciertoDelUsuario'] < 0.3){
            return "bajo";
        }elseif($result['promedioDeAciertoDelUsuario'] > 0.3 && $result['promedioDeAciertoDelUsuario'] < 0.7){
            return "medio";
        }else{
            return "alto";
        }
    }

    public function marcarPreguntaVistaPorUsuario($idUsuario, $idPregunta){
        $query = "INSERT INTO UsuarioPregunta (idUsuario, idPregunta) VALUES (?,?)";
        $stmt = $this->db->connection->prepare($query);
        $stmt->bind_param("ii", $idUsuario, $idPregunta);
        $stmt->execute();
    }
    public function reiniciarRegistroDePreguntasVistasPorUsuario($idUsuario){
        $query = "DELETE FROM UsuarioPregunta WHERE idUsuario = ?";
        $stmt = $this->db->connection->prepare($query);
        $stmt->bind_param("i", $idUsuario);
        $stmt->execute();
    }
    private function getPreguntaAleatoria(array $preguntasNoVistasPorUsuario){
        $preguntas = $preguntasNoVistasPorUsuario;
        $idPreguntaAleatoria = array_rand($preguntas);
        return $preguntas[$idPreguntaAleatoria];
    }
    private function getPreguntasNoVistasPorUsuario($idUsuario,$nivel)
    {
        $dificultad = $this->filtrarPreguntasPorNivelDeUsuario($nivel);

        $queryFuncional = "
            SELECT pr.id AS idPreguntaNoVista
            FROM pregunta pr
            WHERE pr.id NOT IN (
                SELECT up.idPregunta
                FROM usuario u
                JOIN UsuarioPregunta up ON u.id = up.idUsuario
                WHERE u.id = ?
            )
            AND pr.id IN (
                SELECT idPreguntaFiltrada
                FROM PreguntasFiltradasPorNivel
            );";
        $queryFinal = $dificultad . $queryFuncional;
        $stmt = $this->db->connection->prepare($queryFinal);
        $stmt->bind_param("i", $idUsuario);
        $stmt->execute();
        $result = $stmt->get_result();
        $preguntasNoVistasPorUsuario = [];
        while($row = $result->fetch_assoc()){
            $preguntasNoVistasPorUsuario[] = $row["idPreguntaNoVista"];
        }
        return $preguntasNoVistasPorUsuario;
    }
    private function obtenerRespuestasALaPregunta($idPregunta){
        $query = "SELECT p.id as idPregunta, p.descripcion as pregunta, r.descripcion as respuesta, r.id as idRta
              FROM pregunta p 
              JOIN respuesta r ON p.id = r.idPregunta 
              WHERE p.id = ?";

        $stmt = $this->db->connection->prepare($query);
        $stmt->bind_param("i", $idPregunta);
        $stmt->execute();
        $result = $stmt->get_result();

        $data = [
            "pregunta" => [
                "idPregunta" => null,
                "descripcion" => ""
            ],
            "respuestas" => []
        ];

        while ($row = $result->fetch_assoc()) {
            if ($data["pregunta"]["idPregunta"] === null) {
                $data["pregunta"]["idPregunta"] = $row["idPregunta"];
                $data["pregunta"]["descripcion"] = $row["pregunta"];
            }

            $data["respuestas"][] = [
                "respuesta" => $row["respuesta"],
                "idRta" => $row["idRta"]
            ];
        }
        //shuffle($data['respuestas']);
        return $data;
    }
    /*
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



    public function getPregunta($idUsuario){
        $preguntasNoVistasPorUsuario = $this->getPreguntasNoVistasPorUsuario($idUsuario);
        if($preguntasNoVistasPorUsuario){


        $idPregunta = $this->getPreguntaAleatoria($preguntasNoVistasPorUsuario);
        $query = "SELECT p.id as idPregunta, p.descripcion as pregunta, r.descripcion as respuesta, r.id as idRta
              FROM pregunta p 
              JOIN respuesta r ON p.id = r.idPregunta 
              WHERE p.id = ?";

        $stmt = $this->db->connection->prepare($query);
        $stmt->bind_param("i", $idPregunta);
        $stmt->execute();
        $result = $stmt->get_result();

        $data = [
            "pregunta" => [
                "idPregunta" => null,
                "descripcion" => ""
            ],
            "respuestas" => []
        ];

        while ($row = $result->fetch_assoc()) {
            if ($data["pregunta"]["idPregunta"] === null) {
                $data["pregunta"]["idPregunta"] = $row["idPregunta"];
                $data["pregunta"]["descripcion"] = $row["pregunta"];
            }

            $data["respuestas"][] = [
                "respuesta" => $row["respuesta"],
                "idRta" => $row["idRta"]
            ];
        }
        shuffle($data['respuestas']);
        return $data;
        }
        return null;
    }


    public function validarRespuesta($idRespuesta){
        $query = "SELECT * FROM respuesta WHERE id = ?";
        $stmt = $this->db->connection->prepare($query);
        $stmt->bind_param("i", $idRespuesta);
        $stmt->execute();
        $result = $stmt->get_result();
        if($row = $result->fetch_assoc()){
            if($row["esCorrecta"] == 1){
                return true;
            }
        }
        return false;
    }

    public function registrarPreguntaCorrecta($idPartida, $idPregunta){
        $fechaHoraActual = new DateTime();
        $fechaFormateada = $fechaHoraActual->format('Y-m-d H:i:s');
        $query = "INSERT INTO tienen (idPartida, idPregunta,fecha,puntaje) VALUES (?,?,?,?)";
        $puntaje = 1;
        $stmt = $this->db->connection->prepare($query);
        $stmt->bind_param("iisi", $idPartida, $idPregunta,$fechaFormateada,$puntaje);
        $stmt->execute();
    }
    public function registrarPreguntaIncorrecta($idPartida, $idPregunta){
        $fechaHoraActual = new DateTime();
        $fechaFormateada = $fechaHoraActual->format('Y-m-d H:i:s');
        $query = "INSERT INTO tienen (idPartida, idPregunta,fecha,puntaje) VALUES (?,?,?,?)";
        $puntaje = 0;
        $stmt = $this->db->connection->prepare($query);
        $stmt->bind_param("iisi", $idPartida, $idPregunta,$fechaFormateada,$puntaje);
        $stmt->execute();
    }

    private function getPreguntasNoVistasPorUsuario($idUsuario)
    {
        /*$dificultad['dificil'] = "WITH PreguntasDificiles AS (
    SELECT tienen.idPregunta AS idPreguntaDificil,
           AVG(tienen.puntaje) AS promedio
    FROM tienen
    GROUP BY tienen.idPregunta
    HAVING promedio < 0.3
)";
        $dificultad['medio'] = "WITH PreguntasDificiles AS (
    SELECT tienen.idPregunta AS idPreguntaDificil,
           AVG(tienen.puntaje) AS promedio
    FROM tienen
    GROUP BY tienen.idPregunta
    HAVING promedio > 0.3 and < 0.7
)";
        $dificultad['facil'] = "WITH PreguntasDificiles AS (
    SELECT tienen.idPregunta AS idPreguntaDificil,
           AVG(tienen.puntaje) AS promedio
    FROM tienen
    GROUP BY tienen.idPregunta
    HAVING promedio > 0.7
)";

        $queryFuncional = "{$dificultad['dificil']} 
SELECT pr.id AS idPreguntaNoVista
FROM pregunta pr
WHERE pr.id NOT IN (
    SELECT up.idPregunta
    FROM usuario u
    JOIN UsuarioPregunta up ON u.id = up.idUsuario
    WHERE u.id = ?
)
AND pr.id IN (
    SELECT idPreguntaDificil
    FROM PreguntasDificiles
);";*//*
            $queryFuncional = "SELECT pr.id AS idPreguntaNoVista
                   FROM pregunta pr
                   WHERE pr.id NOT IN (
                       SELECT up.idPregunta as id
                       FROM usuario u
                       JOIN UsuarioPregunta up on u.id = up.idUsuario
                       WHERE u.id = ?
                       ORDER BY u.id
                   );";
        $stmt = $this->db->connection->prepare($queryFuncional);
        $stmt->bind_param("i", $idUsuario);
        $stmt->execute();
        $result = $stmt->get_result();
        $preguntasNoVistasPorUsuario = [];
        while($row = $result->fetch_assoc()){
            $preguntasNoVistasPorUsuario[] = $row["idPreguntaNoVista"];
        }
        return $preguntasNoVistasPorUsuario;
    }

    private function getPreguntaAleatoria(array $preguntasNoVistasPorUsuario){
        $preguntas = $preguntasNoVistasPorUsuario;
        $idPreguntaAleatoria = array_rand($preguntas);
        return $preguntas[$idPreguntaAleatoria];
    }
    public function desactivarPartida($idPartida){
        $query = "UPDATE partida SET estado = 'inactivo' WHERE id = ?";
        $stmt = $this->db->connection->prepare($query);
        $stmt->bind_param("i",$idPartida);
        $stmt->execute();
    }

    public function crearPartida($idUsuario){
        $fecha = date('Y-m-d H:i:s');
        $estado = "Activo";
        $query = "INSERT INTO partida(fecha, estado,idUsuario) VALUES (?,?,?)";
        $stmt = $this->db->connection->prepare($query);
        $stmt->bind_param("ssi", $fecha, $estado, $idUsuario);
        $stmt->execute();
    }

    public function getPartida($idUsuario){
        $query = "SELECT id FROM partida WHERE idUsuario = ? AND estado = 'activo'";
        $stmt = $this->db->connection->prepare($query);
        $stmt->bind_param("i", $idUsuario);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function reiniciarRegistroDePreguntasVistasPorUsuario($idUsuario){
        $query = "DELETE FROM UsuarioPregunta WHERE idUsuario = ?";
        $stmt = $this->db->connection->prepare($query);
        $stmt->bind_param("i", $idUsuario);
        $stmt->execute();
    }

    public function marcarPreguntaVistaPorUsuario($idUsuario, $idPregunta){
        $query = "INSERT INTO UsuarioPregunta (idUsuario, idPregunta) VALUES (?,?)";
        $stmt = $this->db->connection->prepare($query);
        $stmt->bind_param("ii", $idUsuario, $idPregunta);
        $stmt->execute();
    }
}*/
}