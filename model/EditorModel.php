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
            $query = "SELECT reporte.id AS id_reporte,
                         reporte.texto AS texto_reporte,
                         usuario.id AS id_usuario,
                         usuario.nombre_completo AS nombre_usuario, 
                         pregunta.id AS id_pregunta,
                         pregunta.descripcion AS descripcion
                         FROM reporte
                         JOIN usuario ON reporte.usuario_id = usuario.id
                         JOIN pregunta ON reporte.pregunta_id = pregunta.id
                         ORDER BY id_reporte ASC;";
            return $this->ejecucionDeConsultaFetchAllSinParametros($query);
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

        // Esto se usa cuando hago varias consultas, para que si ocurre un problema en el medio me cancele todo
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

        private function cambiarEstadoDePreguntaYEliminarReporte($idPregunta, $nuevoEstado) {
            // Cambiar el estado de la pregunta
            $estadoExitoso = $this->cambiarEstadoDeLaPregunta($idPregunta, $nuevoEstado);

            // Eliminar el reporte asociado a la pregunta
            $query = "DELETE FROM reporte WHERE id_pregunta = ?";
            try {
                $stmt = $this->database->connection->prepare($query);
                if (!$stmt) {
                    throw new Exception("Error al preparar la consulta: " . $this->database->connection->error);
                }

                $stmt->bind_param("i", $idPregunta);
                if (!$stmt->execute()) {
                    throw new Exception("Error al ejecutar la consulta: " . $stmt->error);
                }

                // Retorna true si tanto el cambio de estado como la eliminación fueron exitosos
                return $estadoExitoso;
            } catch (Exception $e) {
                // Manejo de errores
                echo "Error: " . $e->getMessage();
                return false;
            }
        }


        private function cambiarEstadoDeLaPregunta($idPregunta, $nuevoEstado){
            $query = "UPDATE pregunta SET estado = ? WHERE id = ?";
            $stmt = $this->database->connection->prepare($query);
            $stmt->bind_param("si", $nuevoEstado, $idPregunta);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                return true;
            } else {
                return false;
            }

        }

        public function darDeAltaReporte($idPregunta) {
            $nuevoEstado = 'APROBADA'; // Activar la pregunta
            // retorna true si se cambio el estado y se eliminó el reporte
            return $this->cambiarEstadoDePreguntaYEliminarReporte($idPregunta, $nuevoEstado);
        }


        public function darDeBajaReporte($idPregunta) {
            $this->ejecutarConTransaccion(function() use ($idPregunta) {
                $nuevoEstado = 'DESACTIVADA';
                $this->cambiarEstadoDePreguntaYEliminarReporte($idPregunta, $nuevoEstado);
            });
            // Retornar true si fue exitoso
            return true;
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
                // En caso de error, registramos el error y retornamos false
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