<?php
class LobbyController {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getUserById($id) {
        $stmt = $this->conn->prepare("SELECT nombre, puntaje FROM usuarios WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>

