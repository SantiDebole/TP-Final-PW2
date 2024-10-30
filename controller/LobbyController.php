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
        $userId = $_SESSION['user_id'];
        $username = $_SESSION['username'];

        // Preparar los datos para la vista
        $data = [
            'lobby' => [
                'loggedUserId' => $userId,
                'username' => $username,
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


    public function verRival(){
        $idBuscado = $_POST["usuarioBuscado"];
        $data['perfilRival'] = $this->model->buscarDatosDeOtrosJugadores($idBuscado);
        var_dump($data);
        $this->presenter->show("perfil",$data);

    }

    public function getUserById($id) { //ESTO ESTA MAL, TIENE QUE IR EN EL MODELO!!!
        $stmt = $this->conn->prepare("SELECT nombre, puntaje FROM usuarios WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function mis_partidas(){
        $idUsuario = $_SESSION['user_id'];

        $resultados= $this->model->traerMisPartidas($idUsuario);
        var_dump($resultados);

        $data = [
            'misPartidas' => $resultados['partidas'],
            'mejorPartida' => $resultados['mejor_partida']
        ];

        $this->presenter->show("misPartidas",$data);

    }
}
?>

