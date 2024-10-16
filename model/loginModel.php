<?php


class LoginModel {
    private $conn;

    public function __construct($conn){
        $this->conn = $conn;
    }
}