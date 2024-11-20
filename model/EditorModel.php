    <?php

        class EditorModel
        {
            private $database;

            public function __construct($database)
            {
                $this->database = $database;
            }

        // devuelve un array multidimensional (un array con arrays asociativos dentro (preguntas reportadas))
            public function obtenerPreguntasReportadas() {
                $query = "SELECT DISTINCT 
                 pregunta.id AS id_pregunta,
                 pregunta.descripcion AS descripcion
             FROM reporte
             JOIN usuario ON reporte.usuario_id = usuario.id
             JOIN pregunta ON reporte.pregunta_id = pregunta.id
             WHERE reporte.estado = 'activa'
             ORDER BY pregunta.id ASC;";
                return $this->ejecucionDeConsultaFetchAllSinParametros($query);
            }


            public function obtenerReportesDeUnaPregunta($id_pregunta)
            {
                $query = "SELECT reporte.id AS id_reporte, 
                     reporte.texto AS texto_reporte, 
                     reporte.estado AS estado_reporte,
                     usuario.id AS id_usuario, 
                     usuario.nombre_completo AS nombre_usuario
              FROM reporte
              JOIN usuario ON reporte.usuario_id = usuario.id
              WHERE reporte.pregunta_id = ? AND reporte.estado = 'activa'
              ORDER BY reporte.id ASC;";
                $stmt = $this->database->connection->prepare($query);
                $stmt->bind_param("i", $id_pregunta);
                $stmt->execute();
                $result = $stmt->get_result();
                return $result->fetch_all(MYSQLI_ASSOC);
            }

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

        // Cambia el estado de los reportes a inactiva para una pregunta específica
        private function cambiarEstadoDeReportes($idPregunta, $nuevoEstadoReporte) {
            $query = "UPDATE reporte SET estado = ? WHERE pregunta_id = ?";
            $stmt = $this->database->connection->prepare($query);
            $stmt->bind_param("si", $nuevoEstadoReporte, $idPregunta);
            $stmt->execute();

            return $stmt->affected_rows > 0;
        }

        // Cambiar el estado de la pregunta
        private function cambiarEstadoDeLaPregunta($idPregunta, $nuevoEstado){
            $query = "UPDATE pregunta SET estado = ? WHERE id = ?";
            $stmt = $this->database->connection->prepare($query);
            $stmt->bind_param("si", $nuevoEstado, $idPregunta);
            $stmt->execute();

            return $stmt->affected_rows > 0;
        }

        // Método para dar de alta (solo cambia el estado de los reportes a 'inactiva')
        public function darDeAltaReporte($idPregunta) {
            $resultado = false;
            // Cambia el estado de los reportes a 'inactiva'
            $this->ejecutarConTransaccion(function() use ($idPregunta, &$resultado) {
                $resultado = $this->cambiarEstadoDeReportes($idPregunta, 'inactiva');
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
                $reportesInactivos = $this->cambiarEstadoDeReportes($idPregunta, 'inactiva');

                // Cambia el estado de la pregunta a 'desactivada'
                $preguntaDesactivada = $this->cambiarEstadoDeLaPregunta($idPregunta, 'desactivada');

                // Si ambas operaciones fueron exitosas, retornamos true
                $resultado = $reportesInactivos && $preguntaDesactivada;
            });

            if (!$resultado) {
                error_log("Error al dar de baja los reportes y la pregunta con ID: $idPregunta");
            }

            return $resultado;
        }





            public function modificarPregunta($idPregunta, $textoPregunta, $idCategoria)
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





            public function modificarRespuesta($idRespuesta, $textoRespuesta)
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


        }