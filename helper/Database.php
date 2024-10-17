<?php

class Database {

    private $conn;

    public function __construct($host, $username, $password, $database) {
            $this->conn = new mysqli($host, $username, $password, $database);
    }

    public function __destruct()
    {
        $this->conn->close();
    }

    public function getUser($username){
        $sql = "SELECT * FROM usuario WHERE nombre_completo = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }


}