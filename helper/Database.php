<?php

class Database {


    public $connection;

    public function __construct($servername, $username, $password, $database, $port) {

        $this->connection = mysqli_connect($servername, $username, $password, $database,$port);

        if (!$this->connection) {
            die("Connection failed: " . mysqli_connect_error());
        }

    }


    public function query($query) {
        $sqlResult = mysqli_query($this->connection, $query);

        // Verifica si la consulta falló y muestra el error
        if (!$sqlResult) {
            die("Error en la consulta SQL: " . mysqli_error($this->connection));
        }

        // Solo intenta fetch_all si el resultado es un objeto, lo que significa que se trata de una consulta SELECT
        if ($sqlResult instanceof mysqli_result) {
            return mysqli_fetch_all($sqlResult, MYSQLI_ASSOC);
        }

        // Si es una consulta DELETE, INSERT o UPDATE, devuelve el resultado de la consulta directamente
        return $sqlResult;
    }

    public function __destruct(){

        mysqli_close($this->connection);

    }


    public function executeQueryConParametros($query, array $variables) {
        try{
        $stmt = $this->connection->prepare($query);
            if (!$stmt) {
                throw new Exception("Error al preparar la consulta: " . $this->connection->error);
            }
        $ordenTiposDeDatos = "";

        foreach ($variables as $item => $valor) {
            $ordenTiposDeDatos .= is_integer($valor) ? "i" : "s";
        }

        $parametros = $this->referenciarValores($variables);

        array_unshift($parametros, $ordenTiposDeDatos); //mandar la variable al principio del array

        $stmt->bind_param(...$parametros);
        $stmt->execute();
            if (!$stmt->execute()) {
                throw new Exception("Error al ejecutar la consulta: " . $stmt->error);
            }
            //return stripos(trim($query),'SELECT') === 0 ? $stmt->affected_rows : $stmt->get_result();
            return $stmt->get_result();
        }catch(Exception $e){
            echo "Error: " . $e->getMessage();
            return false;
        }
        // Si es una consulta SELECT, devuelve el resultado
        /*if (preg_match('/^\s*SELECT/i', $query)) {
           /para verificar si es un select, devolver get_result()
        }*/
    }

    private function referenciarValores($array) {
        $refs = [];
        foreach ($array as $key => $value) {
            $refs[$key] = &$array[$key]; //referencia de c/ elemento
        }
        return $refs;
    }
}


?>






