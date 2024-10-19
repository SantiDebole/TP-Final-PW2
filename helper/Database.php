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

    public function getUser($username){
        $sql = "SELECT * FROM usuario WHERE nombre_completo = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }




}


?>






