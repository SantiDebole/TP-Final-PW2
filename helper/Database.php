<?php

class Database {
    private $database;
    private $password;
    private $username;
    private $host;

    public function __construct($host, $username, $password, $database) {
        $this->host = $host;
        $this->username = $username;
        $this->password = $password;
        $this->database = $database;
    }
}