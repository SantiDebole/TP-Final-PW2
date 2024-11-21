<?php

class PartidaModel
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function ultimaPregunta($idPartida, $idUsuario)
    {
        // Consulta para obtener el ID de la última pregunta
        $query = "
        SELECT p.id
        FROM pregunta p
        JOIN tienen t ON p.id = t.idPregunta
        JOIN partida pa ON t.idPartida = pa.id
        WHERE pa.id = ? AND pa.idUsuario = ?
        ORDER BY t.fecha DESC
        LIMIT 1;
    ";

        $result = $this->db->executeQueryConParametros($query,[$idPartida, $idUsuario]);

        // Verificar si se encontró una pregunta
        if ($result->num_rows > 0) {
            // Obtener el ID de la pregunta
            $row = $result->fetch_assoc();
            return $row['id']; // Devolver solo el ID
        }

        // Si no hay resultados, retornar null
        return null;
    }


    public function validarRespuesta($idRespuesta,$idPartida){
        $pregunta = $this->tienePreguntaDisponible($idPartida);
        if($this->controladorDeExpiracionDePregunta($pregunta['fecha'])&&$this->controlarSiEsRespuestaCorrectaParaPreguntaObtenida($pregunta,$idRespuesta)){
            $this->marcarPreguntaComoCorrecta($pregunta);
            return true;}
        else { $this->marcarPreguntaComoIncorrecta($pregunta);

        }
        $this->marcarPartidaComoFinalizada($idPartida);
        return false;

    }
    public function getPreguntaConRespuestas($idUsuario, $idPartida)
    {
        if ($this->tienePartidaDisponible($idUsuario)) {

            $pregunta = $this->tienePreguntaDisponible($idPartida);
            if ($pregunta) return $this->obtenerRespuestasALaPregunta($pregunta['idPregunta']);


            $nivel = $this->calcularNivelDelUsuario($idUsuario);
            $preguntasNoVistasPorUsuario = $this->getPreguntasNoVistasPorUsuario($idUsuario, $nivel);
            if ($preguntasNoVistasPorUsuario) {
                $idPregunta = $this->getPreguntaAleatoria($preguntasNoVistasPorUsuario);
                $this->registrarPreguntaEnTienen($idPartida, $idPregunta);
                $this->registrarPreguntaEnUsuarioPregunta($idUsuario, $idPregunta);
                return $this->obtenerRespuestasALaPregunta($idPregunta);
            } else {

                $this->reiniciarRegistroDePreguntasVistasPorUsuario($idUsuario);
                $this->getPreguntaConRespuestas($idUsuario, $idPartida);
            }
        }
    }
    public function crearPartida($idUsuario)
    {

        $fecha = date('Y-m-d H:i:s');
        $estado = "Activo";
        $query = "INSERT INTO partida(fecha, estado,idUsuario) VALUES (?,?,?)";
        $this->db->executeQueryConParametros($query,[$fecha, $estado, $idUsuario]);
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
                    $this->marcarPartidaComoFinalizada($partidaDisponible['id']);
                    return false;
                }
            } else return true;
        }
        return false;

    }
    public function terminarPartida($idUsuario)
    {
        $partidaDisponible = $this->tienePartidaDisponible($idUsuario);
        var_dump($partidaDisponible['id']);
        $pregunta = $this->tienePreguntaDisponible($partidaDisponible['id']);
        $this->marcarPartidaComoFinalizada($partidaDisponible['id']);
        $this->marcarPreguntaComoIncorrecta($pregunta);

    }
    private function marcarPartidaComoFinalizada($partidaDisponible)
    {
        $query = "update partida
        set estado = 'inactivo'
        where id = ?";
        $this->db->executeQueryConParametros($query,[$partidaDisponible]);

    }
    private function marcarPreguntaComoIncorrecta($pregunta)
    {
        $query = "update tienen
        set puntaje = 0
        where idPartida = ? and idPregunta = ? and fecha= ?";
        $this->db->executeQueryConParametros($query,[$pregunta['idPartida'], $pregunta['idPregunta'], $pregunta['fecha']]);
    }
    private function controladorDeExpiracionDePregunta($horario)
    {

        $fechaPregunta = new DateTime($horario);
        $fechaActual = new DateTime();
        $intervalo = $fechaPregunta->diff($fechaActual); //Diff devuelve un DateInterval
        if ($intervalo->i < 1 && $intervalo->h == 0 && $intervalo->d == 0 && $intervalo->m == 0 && $intervalo->y == 0) return true;
        return false;
    }
    public function tienePartidaDisponible($idUsuario)
    {
        $query = "SELECT id 
        from partida
        where idUsuario = ?
        and estado = 'activo'";
        $result = $this->db->executeQueryConParametros($query,[$idUsuario]);
        return $result->fetch_assoc();


    }
    private function tienePreguntaDisponible($idPartida)
    {
        $query = "SELECT idPartida, idPregunta, fecha
        from tienen
        where idPartida = ?
        and puntaje IS NULL";
        $result = $this->db->executeQueryConParametros($query,[$idPartida]);
        return $result->fetch_assoc();
    }
    private function filtrarPreguntasPorNivelDeUsuario($nivel): string
    {
        $dificultad["alto"]= '
                WITH PreguntasFiltradasPorNivel AS (
                    SELECT tienen.idPregunta AS idPreguntaFiltrada,
                           AVG(tienen.puntaje) AS promedio
                    FROM tienen
                    GROUP BY tienen.idPregunta
                    HAVING promedio < 0.3
                )';
        $dificultad["medio"]= '
                WITH PreguntasFiltradasPorNivel AS (
                    SELECT tienen.idPregunta AS idPreguntaFiltrada,
                           AVG(tienen.puntaje) AS promedio
                    FROM tienen
                    GROUP BY tienen.idPregunta
                    HAVING promedio > 0.3 AND promedio < 0.7
                )';
        $dificultad["bajo"]='
                WITH PreguntasFiltradasPorNivel AS (
                    SELECT tienen.idPregunta AS idPreguntaFiltrada,
                           AVG(tienen.puntaje) AS promedio
                    FROM tienen
                    GROUP BY tienen.idPregunta
                    HAVING promedio > 0.7
                )';

        return $dificultad[$nivel];
        /*
           $dificultad[] = "";
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
           return $dificultad;*/
    }
    private function calcularNivelDelUsuario($idUsuario)
    {
        $query = "SELECT AVG(tienen.puntaje) AS promedioDeAciertoDelUsuario
                    FROM tienen join partida on tienen.idPartida = partida.id
                    where partida.idUsuario = ?
                    GROUP BY partida.idUsuario";
        $result = $this->db->executeQueryConParametros($query,[$idUsuario]);
        $result = $result->fetch_assoc();
        if($result['promedioDeAciertoDelUsuario'] < 0.3){
            return "bajo";
        }elseif($result['promedioDeAciertoDelUsuario'] > 0.3 && $result['promedioDeAciertoDelUsuario'] < 0.7){
            return "medio";
        }else{
            return "alto";
        }
    }
    private function marcarPreguntaComoCorrecta($pregunta){
        $query="update tienen
        set puntaje = 1
      where idPartida = ? and idPregunta = ? and fecha = ?";
        $this->db->executeQueryConParametros($query,[$pregunta['idPartida'],$pregunta['idPregunta'],$pregunta['fecha']]);
    }
    public function reiniciarRegistroDePreguntasVistasPorUsuario($idUsuario){
        $query = "DELETE FROM UsuarioPregunta WHERE idUsuario = ?";
        $this->db->executeQueryConParametros($query,[$idUsuario]);
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
            AND (pr.estado = 'activa' or pr.estado = 'reportada')
                        AND pr.id IN (
                            SELECT idPreguntaFiltrada
                            FROM PreguntasFiltradasPorNivel
                        );";
        $queryFinal = $dificultad . $queryFuncional;
        $result = $this->db->executeQueryConParametros($queryFinal,[$idUsuario]);
        $preguntasNoVistasPorUsuario = [];
        while($row = $result->fetch_assoc()){
            $preguntasNoVistasPorUsuario[] = $row["idPreguntaNoVista"];
        }
        return $preguntasNoVistasPorUsuario;
    }
    private function obtenerRespuestasALaPregunta($idPregunta){
        $query = "SELECT p.id as idPregunta, p.descripcion as pregunta, c.descripcion as categoria_descripcion, c.color as color_categoria, r.descripcion as respuesta, r.id as idRta
              FROM pregunta p 
              JOIN respuesta r ON p.id = r.idPregunta 
              JOIN categoria c ON p.idCategoria = c.id
              WHERE p.id = ?";

        $result = $this->db->executeQueryConParametros($query,[$idPregunta]);

        $data = [
            "pregunta" => [
                "idPregunta" => null,
                "descripcion" => "",
                "categoria_descripcion" => "",
                "color_categoria" => "",
            ],
            "respuestas" => []
        ];

        while ($row = $result->fetch_assoc()) {
            if ($data["pregunta"]["idPregunta"] === null) {
                $data["pregunta"]["idPregunta"] = $row["idPregunta"];
                $data["pregunta"]["descripcion"] = $row["pregunta"];
                $data["pregunta"]["categoria_descripcion"] = $row["categoria_descripcion"];
                $data["pregunta"]["color_categoria"] = $row["color_categoria"];
            }

            $data["respuestas"][] = [
                "respuesta" => $row["respuesta"],
                "idRta" => $row["idRta"]
            ];
        }
        //shuffle($data['respuestas']);
        return $data;
    }
    private function controlarSiEsRespuestaCorrectaParaPreguntaObtenida($pregunta,$idRespuesta){
        var_dump($pregunta['idPregunta']);
        $query="SELECT 1 
FROM pregunta p
JOIN respuesta r ON p.id = r.idPregunta
WHERE r.esCorrecta = 1 AND r.id = ? and p.id=  ?
;";
        $result = $this->db->executeQueryConParametros($query,[$idRespuesta,$pregunta['idPregunta']]);
        if ($result->num_rows > 0) return true;
        return false;
    }
    private function registrarPreguntaEnTienen($idPartida, $idPregunta){
        $fecha = date('Y-m-d H:i:s');
        $query = "INSERT INTO tienen (idPartida, idPregunta, fecha) VALUES (?,?, ?)";
        $this->db->executeQueryConParametros($query,[$idPartida, $idPregunta, $fecha]);
    }
    private function registrarPreguntaEnUsuarioPregunta($idUsuario, $idPregunta){
        $query = "INSERT INTO usuarioPregunta (idUsuario, idPregunta) VALUES (?,?)";
        $this->db->executeQueryConParametros($query,[$idUsuario, $idPregunta]);
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
        $result = $this->db->executeQueryConParametros($query,[$idPartida, $idUsuario]);
        $resultado = $result->fetch_assoc();
        return $resultado ? $resultado['total_puntaje'] : 0;

    }
    public function tiempoRestanteDeRespuesta($idPartida){
        $query="select fecha
        from tienen
        where puntaje is null 
        and idPartida = ?";
        $result = $this->db->executeQueryConParametros($query,[$idPartida]);
        $resultado = $result->fetch_assoc();
        $fecha=$resultado['fecha'];
        $fechaPregunta = new DateTime($fecha);
        $fechaActual = new DateTime();
        $intervalo = $fechaPregunta->diff($fechaActual);
        return 60-(($intervalo->days * 24 * 60 * 60)+($intervalo->h * 60 * 60)+($intervalo->i * 60)+$intervalo->s);
    }



}