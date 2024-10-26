<?php

class LoginModel {
    private $db;

    public function __construct($db){
        $this->db = $db;
    }

    public function validateLogin($username, $password){
        $user = $this->getUser($username);

        if($user){
            if($password === $user["password"]){
                if(isset($_SESSION["auth_error"])){
                    unset($_SESSION["auth_error"]);
                }
                $_SESSION["loggedUserId"] = $user["id"];
                $_SESSION["loggedUsername"] = $user["usuario"];
                return true;
            }
        }
        $_SESSION["auth_error"] = 1;
        return false;
    }

    public function getUser($username){
        $sql = "SELECT * FROM usuario WHERE usuario = ? and esta_verificado =1";
        $stmt = $this->db->connection->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
}