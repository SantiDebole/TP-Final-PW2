<?php
class LobbyController {
    private $model;

    private $presenter;

    public function __construct($model,$presenter){

        $this->model = $model;
        $this->presenter = $presenter;
    }



    public function listar() {
        // Obtengo rol del usuario desde la sesión
        $rol = $_SESSION['rol'];

        // Preparar los datos para la vista
        $data = [
            'lobby' => [
                'loggedUserId' => $_SESSION['user_id'],
                'username' => $_SESSION['username'],
                'rol' => $rol
            ],
            'rol' => $rol,
            'isAdmin' => ($rol === 'a'),
            'isEditor' => ($rol === 'e'),
            'isPlayer' => ($rol === 'ur'),
        ];

        // Mostrar la vista del lobby con los datos
        $this->presenter->show("lobby", $data);
    }




    public function getUserById($id) {
        $stmt = $this->conn->prepare("SELECT nombre, puntaje FROM usuarios WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>

