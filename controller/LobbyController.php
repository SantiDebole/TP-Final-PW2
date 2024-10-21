<?php
class LobbyController {
    private $model;

    private $presenter;

    public function __construct($model,$presenter){

        $this->model = $model;
        $this->presenter = $presenter;
    }

    public function listar(){

        // Obtener los valores guardados en la sesión
        $loggedUserId = $_SESSION["loggedUserId"];
        $data = $_SESSION["lobby"];

        var_dump($loggedUserId);
        //var_dump($username);


        $this->presenter->show("lobby", $data);
    }




    public function getUserById($id) {
        $stmt = $this->conn->prepare("SELECT nombre, puntaje FROM usuarios WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>

