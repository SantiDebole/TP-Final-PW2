<?php
class UserModel {
    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function getUserById($id) {
        // Preparar la consulta SQL usando la extensión mysqli
        $stmt = $this->database->prepare("SELECT nombre, puntaje FROM usuarios WHERE id = ?");

        // Vincular el parámetro y ejecutar la consulta
        $stmt->bind_param("i", $id); // "i" indica que el parámetro es un entero
        $stmt->execute();

        // Obtener el resultado
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
}
?>
