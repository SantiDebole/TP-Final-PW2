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
                if(isset($_SESSION["auth_error"])){
                    unset($_SESSION["auth_error"]);
                }
                $_SESSION["loggedUserId"] = $user["id"];
                return true;
            }
        }
        $_SESSION["auth_error"] = 1;
        return false;
    }
}