<?php


class LoginModel {
    private $db;

    public function __construct($db){
        $this->db = $db;
    }

    public function validateLogin($username, $password){
        $user = $this->db->getUser($username);
        if($user){
            if($password === $user["contrasena"]){
                return true;
            }
        }
        return false;
    }
}