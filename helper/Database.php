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

    public function query($sql){
        return $this->conn->query($sql);
    }


}