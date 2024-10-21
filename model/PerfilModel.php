<?php

class PerfilModel
{
    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function transformImagePath($usuario): array{


        $usuario["foto_perfil"] = "/public/image/" .$usuario["foto_perfil"];


        return $usuario;
    }

    public function traerPerfil() {

        $usuarioId = $_SESSION["loggedUserId"];

        $sql= "SELECT * FROM usuario WHERE id = ?";
        $stmt = $this->database->connection->prepare($sql);
        $stmt->bind_param("i", $usuarioId);
        $stmt->execute();
        // Obtener el resultado
        $result = $stmt->get_result();
        $usuario = $result->fetch_assoc(); // Esto devuelve un array asociativo
        var_dump($usuario);



       $usuario = $this->transformImagePath($usuario);


        // Si quieres depurar o ver el resultado
        echo '<br>';
        echo '<br>';
        echo '<br>';
        echo 'SOY LA FOTO DE PERFIL';

        echo '<br>';

        var_dump($usuario['foto_perfil']);


        return $usuario;

        /*if ($usuario) {
            $_SESSION['usuario'] = $usuario; // Guardar datos actualizados en sesión
            include 'perfil.php';
        } else {
            echo "Usuario no encontrado.";
        }*/
    }

    public function editarPerfil() {
        global $pdo;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuarioId = $_SESSION['usuario']['id'];

            // Sanitizar y validar las entradas
            $nombre = htmlspecialchars(trim($_POST['nombreCompleto']));
            $username = htmlspecialchars(trim($_POST['username']));
            $email = htmlspecialchars(trim($_POST['email']));
            $ciudad = htmlspecialchars(trim($_POST['ciudad']));
            $pais = htmlspecialchars(trim($_POST['pais']));
            $genero = htmlspecialchars(trim($_POST['genero']));

            $stmt = $pdo->prepare("UPDATE usuarios SET nombre = ?, username = ?, email = ?, ciudad = ?, pais = ?, genero = ? WHERE id = ?");
            if ($stmt->execute([$nombre, $username ,$email, $ciudad, $pais, $genero, $usuarioId])) {
                // Actualizar la sesión con los nuevos datos
                $_SESSION['usuario']['nombre'] = $nombre;
                $_SESSION['usuario']['email'] = $email;

                // Mensaje de éxito (puedes redirigir o mostrar en la vista)
                header('Location: perfil.php?mensaje=Perfil actualizado exitosamente');
                exit;
            } else {
                echo "Error al actualizar el perfil.";
            }
        }
    }



}