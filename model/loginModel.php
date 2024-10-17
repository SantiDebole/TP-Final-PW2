<?php


class LoginModel {
    private $db;

    public function __construct($db){
        $this->db = $db;
    }

    public function validateLogin($username, $password){
        $sql = "SELECT * FROM usuario WHERE nombre_completo = '$username' AND contrasena = '$password'";
        $resultado = $this->db->query($sql);
        return $resultado->fetch_assoc();
    }
}