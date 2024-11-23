<?php

class EditorModel
{
    private $database;
    const ESTADO_PENDIENTE = 'pendiente';
    const ESTADO_ACTIVA = 'activa';
    const ESTADO_DESACTIVADA = 'desactivada';
    const ESTADO_INACTIVA = 'inactiva';
    public function __construct($database)
    {
        $this->database = $database;
    }



    // Métodos para que el editor cree preguntas
    public function agregarPreguntaConUnaRespuestaCorrectaYDosIncorrectasYCambiarDeEstado(
        $preguntaSugerida,
        $respuestaCorrecta,
        $respuestaIncorrecta1,
        $respuestaIncorrecta2,
        $estado,
        $idCategoria
    ) {
        // Iniciar transacción
        $this->database->connection->begin_transaction();

        try {
            // Insertar la pregunta
            $sqlPregunta = "INSERT INTO pregunta (descripcion, estado, idCategoria) VALUES (?, ?, ?)";
            $stmtPregunta = $this->database->connection->prepare($sqlPregunta);
            $stmtPregunta->bind_param("ssi", $preguntaSugerida, $estado, $idCategoria);
            $stmtPregunta->execute();
            $idPregunta = $this->database->connection->insert_id;

            // Insertar respuestas
            $sqlRespuestas = "INSERT INTO respuesta (descripcion, esCorrecta, idPregunta) VALUES 
                          (?, 1, ?), (?, 0, ?), (?, 0, ?)";
            $stmtRespuestas = $this->database->connection->prepare($sqlRespuestas);
            $stmtRespuestas->bind_param(
                "sisisi",
                $respuestaCorrecta, $idPregunta,
                $respuestaIncorrecta1, $idPregunta,
                $respuestaIncorrecta2, $idPregunta
            );
            $stmtRespuestas->execute();

            // Confirmar transacción
            $this->database->connection->commit();
        } catch (Exception $e) {
            // Revertir transacción en caso de error
            $this->database->connection->rollback();
            throw $e;
        }
    }

    // Métodos para la gestión de preguntas reportadas

    // Retorna un array multidimensional (un array con arrays asociativos dentro (preguntas reportadas))
    public function obtenerPreguntasReportadas() {
        $query = "SELECT DISTINCT 
                 pregunta.id AS id_pregunta,
                 pregunta.descripcion AS descripcion
             FROM reporte
             JOIN usuario ON reporte.usuario_id = usuario.id
             JOIN pregunta ON reporte.pregunta_id = pregunta.id
             WHERE reporte.estado = 'activo'
             ORDER BY pregunta.id ASC;";
        return $this->ejecucionDeConsultaFetchAllSinParametros($query);
    }
    // Retorna un array asociativo de las respuestas de una pregunta determinada
    public function obtenerRespuestasDeUnaPregunta($id_pregunta) {
        $query = "SELECT respuesta.id AS id_respuesta, 
                                 respuesta.descripcion AS descripcion, 
                                 respuesta.esCorrecta AS es_correcta
                          FROM respuesta
                          WHERE respuesta.idPregunta = ?
                          ORDER BY es_correcta DESC;";
        $stmt = $this->database->connection->prepare($query);
        $stmt->bind_param("i", $id_pregunta);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Retorna los reportes asociados a una pregunta
    public function obtenerReportesDeUnaPregunta($id_pregunta)
    {
        $query = "SELECT reporte.id AS id_reporte, 
                     reporte.texto AS texto_reporte, 
                     reporte.estado AS estado_reporte,
                     usuario.id AS id_usuario, 
                     usuario.nombre_completo AS nombre_usuario
              FROM reporte
              JOIN usuario ON reporte.usuario_id = usuario.id
              WHERE reporte.pregunta_id = ? AND reporte.estado = ?
              ORDER BY reporte.id ASC;";
        return $this->ejecucionDeConsultaFetchAllConParametros($query, [$id_pregunta, self::ESTADO_ACTIVA]);
    }



    // Método para dar de alta (solo cambia el estado de los reportes a 'inactiva')
    public function darDeAltaReporte($idPregunta): bool
    {
        $resultado = false;
        // Cambia el estado de los reportes a 'inactiva'
        $this->ejecutarConTransaccion(function() use ($idPregunta, &$resultado) {
            $resultado = $this->cambiarEstadoDeReportes($idPregunta, self::ESTADO_INACTIVA);
        });

        if (!$resultado) {
            error_log("Error al dar de alta los reportes de la pregunta con ID: $idPregunta");
        }

        return $resultado;
    }

    // Método para dar de baja (cambia el estado de los reportes a 'inactiva' y la pregunta a 'desactivada')
    public function darDeBajaReporte($idPregunta) {
        $resultado = false;
        $this->ejecutarConTransaccion(function() use ($idPregunta, &$resultado) {
            // Cambia el estado de los reportes a 'inactiva'
            $reportesInactivos = $this->cambiarEstadoDeReportes($idPregunta, self::ESTADO_INACTIVA);

            // Cambia el estado de la pregunta a 'desactivada'
            $preguntaDesactivada = $this->cambiarEstadoDeLaPregunta($idPregunta, self::ESTADO_DESACTIVADA);

            // Si ambas operaciones fueron exitosas, retornamos true
            $resultado = $reportesInactivos && $preguntaDesactivada;
        });

        if (!$resultado) {
            error_log("Error al dar de baja los reportes y la pregunta con ID: $idPregunta");
        }

        return $resultado;
    }

    // Cambia el estado de los reportes a inactiva para una pregunta específica
    private function cambiarEstadoDeReportes($idPregunta, $nuevoEstadoReporte): bool
    {
        $query = "UPDATE reporte SET estado = ? WHERE pregunta_id = ?";
        $stmt = $this->database->connection->prepare($query);
        $stmt->bind_param("si", $nuevoEstadoReporte, $idPregunta);
        $stmt->execute();

        return $stmt->affected_rows > 0;
    }

    // Modifica la pregunta reportada
    public function modificarPregunta($idPregunta, $textoPregunta, $idCategoria): bool
    {
        try {
            // Preparo la consulta de actualización
            $query = "UPDATE pregunta SET descripcion = ?, idCategoria = ? WHERE id = ?";
            $stmt = $this->database->connection->prepare($query);

            if (!$stmt) {
                throw new Exception("Error al preparar la consulta de actualización: " . $this->database->connection->error);
            }

            // Vinculo los parámetros
            $stmt->bind_param("sii", $textoPregunta, $idCategoria, $idPregunta);

            // Ejecuto la consulta
            $stmt->execute();

            // Verifico si la actualización fue exitosa
            if ($stmt->affected_rows > 0) {
                return true; // Modificación exitosa
            } else {
                return false; // No se realizó ningún cambio
            }
        } catch (Exception $e) {
            // En caso de error, registramos el error y retornamos falso
            error_log("Error al modificar la pregunta: " . $e->getMessage());
            return false;
        }
    }


    // Cambiar el estado de la pregunta
    private function cambiarEstadoDeLaPregunta($idPregunta, $nuevoEstado): bool
    {
        $query = "UPDATE pregunta SET estado = ? WHERE id = ?";
        $stmt = $this->database->connection->prepare($query);
        $stmt->bind_param("si", $nuevoEstado, $idPregunta);
        $stmt->execute();

        return $stmt->affected_rows > 0;
    }


    public function modificarRespuesta($idRespuesta, $textoRespuesta): bool
    {
        try {
            $query = "UPDATE respuesta SET descripcion = ? WHERE id = ?";
            $stmt = $this->database->connection->prepare($query);

            if (!$stmt) {
                error_log("Error al preparar la consulta de actualización: " . $this->database->connection->error);
                return false;
            }

            $stmt->bind_param("si", $textoRespuesta, $idRespuesta);
            $result = $stmt->execute();

            if ($result) {
                if ($stmt->affected_rows > 0) {
                    return true;  // Respuesta actualizada con éxito
                } else {
                    return false;  // No se actualizó ninguna fila
                }
            } else {
                error_log("Error al ejecutar la consulta: " . $stmt->error);
                return false;  // Error al ejecutar la consulta
            }
        } catch (Exception $e) {
            error_log("Error al modificar la respuesta: " . $e->getMessage());
            return false;
        }
    }

    public function obtenerPreguntaPorId($idPregunta)
    {
        $query = "SELECT id, descripcion, idCategoria FROM pregunta WHERE id = ?";
        $stmt = $this->database->connection->prepare($query);
        $stmt->bind_param("i", $idPregunta);
        $stmt->execute();
        $result = $stmt->get_result();


        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        } else {
            return null;
        }
    }

    public function obtenerRespuestaPorId($idRespuesta)
    {
        try {

            $query = "SELECT id, descripcion FROM respuesta WHERE id = ?";
            $stmt = $this->database->connection->prepare($query);


            if (!$stmt) {
                error_log("Error al preparar la consulta: " . $this->database->connection->error);
                return null;
            }

            $stmt->bind_param("i", $idRespuesta);
            $stmt->execute();
            $result = $stmt->get_result();

            // Verificar si la consulta devuelve resultados
            if ($result->num_rows > 0) {
                return $result->fetch_assoc(); // Retorna la respuesta encontrada
            } else {
                return null; // No se encontró la respuesta
            }

        } catch (Exception $e) {
            // Manejo de errores
            error_log("Error al obtener la respuesta por ID: " . $e->getMessage());
            return null;
        }
    }

    // Gestion de preguntas sugeridas

    public function obtenerPreguntasSugeridas() {
        $query = "SELECT 
                 pregunta.id AS id_pregunta,
                 pregunta.descripcion AS descripcion
             FROM pregunta
             WHERE pregunta.estado = ?
             ORDER BY pregunta.id ASC;";
        return $this->ejecucionDeConsultaFetchAllConParametros($query, [self::ESTADO_PENDIENTE]);
    }

    public function aprobarSugerencia($idPregunta): bool
    {
        $resultado = $this->cambiarEstadoDeLaPregunta($idPregunta, self::ESTADO_ACTIVA);

        if (!$resultado) {
            error_log("Error al aprobar la sugerencia con ID: $idPregunta");
        }

        return $resultado;
    }


    public function rechazarSugerencia($idPregunta): bool
    {
        $resultado = $this->cambiarEstadoDeLaPregunta($idPregunta, self::ESTADO_DESACTIVADA);

        if (!$resultado) {
            error_log("Error al rechazar la sugerencia con ID: $idPregunta");
        }

        return $resultado;
    }



    // Métodos adicionales



    // Ejecuta el callback dentro de una transacción para asegurar consistencia
    private function ejecutarConTransaccion($callback) {
        $this->database->connection->begin_transaction();

        try {
            // Ejecutar el callback proporcionado, que contiene la lógica de la transacción
            $callback();

            // Si todo sale bien, confirmamos la transacción
            $this->database->connection->commit();
        } catch (Exception $e) {
            // Si ocurre un error, revertimos la transacción
            $this->database->connection->rollback();
            // Manejo de error (puede loguearse, lanzarse una excepción, etc.)
            echo "Error en la transacción: " . $e->getMessage();
        }
    }


    private function ejecucionDeConsultaFetchAllSinParametros($query)
    {
        try {

            $stmt = $this->database->connection->prepare($query);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result->fetch_all(MYSQLI_ASSOC);
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    private function ejecucionDeConsultaFetchAllConParametros($query, $parametros)
    {
        try {
            $stmt = $this->database->connection->prepare($query);

            // Vincula parámetros si existen
            if (!empty($parametros)) {
                $tipos = str_repeat("s", count($parametros)); // Asume que todos los parámetros son cadenas
                $stmt->bind_param($tipos, ...$parametros);
            }

            $stmt->execute();
            $result = $stmt->get_result();
            return $result->fetch_all(MYSQLI_ASSOC);
        } catch (Exception $e) {
            error_log("Error en la consulta: " . $e->getMessage());
            return [];
        }
    }



}