<?php

class EditorModel
{
    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function obtenerPreguntasReportadas() {
        $query = "SELECT reporte.id AS id_reporte,
                     reporte.texto AS texto_reporte,
                     usuario.id AS id_usuario,
                     usuario.nombre_completo AS nombre_usuario, 
                     pregunta.id AS id_pregunta,
                     pregunta.descripcion AS texto_pregunta 
                     FROM reporte
                     JOIN usuario ON reporte.id_usuario = usuario.id
                     JOIN pregunta ON reporte.id_pregunta = pregunta.id
                     ORDER BY id_reporte ASC;";
        return $this->ejecucionDeConsultaFetchAllSinParametros($query);
    }

    public function cambiarEstadoDeLaPregunta($idPregunta, $nuevoEstado){
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

    public function darDeAltaReporte($idPregunta)
    {

        $queryActualizarPregunta = "UPDATE pregunta SET estado = 'APROBADA' WHERE id = ?";
        $stmt = $this->database->connection->prepare($queryActualizarPregunta);
        $stmt->bind_param("i", $idPregunta);
        $stmt->execute();

        $queryEliminarReporte = "DELETE FROM reporte WHERE id_pregunta = ?";
        $stmt = $this->database->connection->prepare($queryEliminarReporte);
        $stmt->bind_param("i", $idPregunta);
        $stmt->execute();


        if ($stmt->affected_rows > 0) {
            return true;
        } else {
            return false; }
    }

    public function darDeBajaReporte($idPregunta)
    {

        $queryActualizarPregunta = "UPDATE pregunta SET estado = 'DESACTIVADA' WHERE id = ?";
        $stmt = $this->database->connection->prepare($queryActualizarPregunta);
        $stmt->bind_param("i", $idPregunta);
        $stmt->execute();

        $queryEliminarReporte = "DELETE FROM reporte WHERE id_pregunta = ?";
        $stmt = $this->database->connection->prepare($queryEliminarReporte);
        $stmt->bind_param("i", $idPregunta);
        $stmt->execute();


        if ($stmt->affected_rows > 0) {
            return true;
        } else {
            return false; }
    }

    /*
        Este metodo es mucho mejor ya que evalua que se cumplan ambas modificaciones de la BDD,
         si algo pasa en el medio vuelve todo a como estaba antes

           public function darDeBajaReporte($idPregunta)
    {
        // Empezamos una transacción
        $this->database->connection->begin_transaction();

        try {
            // Actualizar el estado de la pregunta
            $queryActualizarPregunta = "UPDATE pregunta SET estado = 'DESACTIVADA' WHERE id = ?";
            $stmt = $this->database->connection->prepare($queryActualizarPregunta);
            $stmt->bind_param("i", $idPregunta);
            $stmt->execute();

            if ($stmt->affected_rows === 0) {
                throw new Exception('No se actualizó el estado de la pregunta');
            }

            // Eliminar el reporte
            $queryEliminarReporte = "DELETE FROM reporte WHERE id_pregunta = ?";
            $stmt = $this->database->connection->prepare($queryEliminarReporte);
            $stmt->bind_param("i", $idPregunta);
            $stmt->execute();

            if ($stmt->affected_rows === 0) {
                throw new Exception('No se eliminó el reporte');
            }

            // Si todo salió bien, hacer commit de la transacción
            $this->database->connection->commit();
            return true;
        } catch (Exception $e) {
            // Si algo falla, hacer rollback de la transacción
            $this->database->connection->rollback();
            // Puedes registrar el error en un log
            // error_log($e->getMessage());
            return false;
        }
    }
        */
    public function modificarPregunta($idPregunta, $nuevaDescripcion, $nuevaCategoria)
    {
        $query = "UPDATE pregunta 
              SET descripcion = ?, categoria = ?
              WHERE id = ?";
        $stmt = $this->database->connection->prepare($query);
        $stmt->bind_param("sii", $nuevaDescripcion, $nuevaCategoria, $idPregunta);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            return true;
        } else {
            return false;
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