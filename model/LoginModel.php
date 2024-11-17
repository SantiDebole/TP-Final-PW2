<?php

class LoginModel {
    private $db;

    public function __construct($db){
        $this->db = $db;
    }

    public function validateLogin($username, $password){
        $user = $this->getUser($username);

        if($user && $password === $user["password"]){
            return $user['id'];

        }
        return false;
    }

    public function getUser($username){
        $sql = "SELECT * FROM usuario WHERE usuario = ? and esta_verificado =1";
        $stmt = $this->db->connection->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function getUserRol($userId) {
        $sql = "SELECT rol FROM usuario WHERE id = ?";
        $stmt = $this->db->connection->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();

        $result = $stmt->get_result()->fetch_assoc();
        return $result ? $result['rol'] : null; //retorna null si no lo encuentra
    }


}