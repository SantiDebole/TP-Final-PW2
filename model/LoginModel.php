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
        $result = $this->db->executeQueryConParametros($sql,[$username]);
        return $result->fetch_assoc();
    }

    public function getUserRol($userId) {
        $sql = "SELECT rol FROM usuario WHERE id = ?";
        $result = $this->db->executeQueryConParametros($sql,[$userId]);
        $resultado = $result->fetch_assoc();
        return $resultado ? $resultado['rol'] : null; //retorna null si no lo encuentra
    }


}