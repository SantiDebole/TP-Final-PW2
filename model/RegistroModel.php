<?php

class RegistroModel
{

    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function registrar($datos_usuario)
    {
        $validacionUsuario = $this->validarUsuario($datos_usuario['usuario']);
        if($validacionUsuario == true){
            return "Usuario ya existente";
        }
        $validacionEmail = $this->validarEmail($datos_usuario['email']);
        if($validacionEmail == true){
            return "Email ya existente";
        }
        $guardadoDeFotoDePerfil = $this->guardarFotoDePerfil($datos_usuario['foto_perfil']);

        $sql = "INSERT INTO usuario (nombre_completo, fecha_nacimiento, genero, email, usuario, password, rol, foto_perfil, pais, ciudad) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        // Preparar la consulta
        $stmt = $this->database->connection->prepare($sql);

        // Verificar si la preparación fue exitosa
        if ($stmt) {
            // Enlazar parámetros (s: string, d: double, i: integer, b: blob)
            $hashed_password = password_hash($datos_usuario['password'], PASSWORD_DEFAULT);
            $stmt->bind_param("ssssssssss",
                $datos_usuario['nombre_completo'],
                $datos_usuario['fecha_nacimiento'],
                $datos_usuario['genero'],
                $datos_usuario['email'],
                $datos_usuario['usuario'],
                $hashed_password,
                $datos_usuario['rol'],
                $datos_usuario['foto_perfil'],
                $datos_usuario['pais'],
                $datos_usuario['ciudad']
            );





            if ($stmt->execute()) {

                $registro="exitoso";


            } else {
                $registro="fallo";


            }
            $stmt->close();
            return $registro;


        }


    }

    private function validarUsuario($usuario)
    {
        $sql = "SELECT 1 FROM usuario WHERE usuario=?";
        $stmt = $this->database->connection->prepare($sql);

        // Verificar si la preparación fue exitosa
        if ($stmt) {
            // Enlazar el parámetro (s: string)
            $stmt->bind_param("s", $usuario);

            // Ejecutar la consulta
            if ($stmt->execute()) {
                // Obtener el resultado
                $stmt->store_result();

                // Verificar si existe al menos una fila
                if ($stmt->num_rows > 0) {
                    // Si hay una fila, el usuario ya existe
                    $stmt->close();
                    return true;
                } else {
                    // Si no hay filas, el usuario no existe
                    $stmt->close();
                    return false;
                }
            } else {
                // Si hay un error en la ejecución
                $stmt->close();
                return "error en la consulta";
            }
        }

        // Si la preparación de la consulta falla, devolver false
        return "error en la preparacion de la consulta";
    }

    private function validarEmail($email)
    {
        $sql = "SELECT 1 FROM usuario WHERE email=?";
        $stmt = $this->database->connection->prepare($sql);

        // Verificar si la preparación fue exitosa
        if ($stmt) {
            // Enlazar el parámetro (s: string)
            $stmt->bind_param("s", $email);

            // Ejecutar la consulta
            if ($stmt->execute()) {
                // Obtener el resultado
                $stmt->store_result();

                // Verificar si existe al menos una fila
                if ($stmt->num_rows > 0) {
                    // Si hay una fila, el usuario ya existe
                    $stmt->close();
                    return true;
                } else {
                    // Si no hay filas, el usuario no existe
                    $stmt->close();
                    return false;
                }
            } else {
                // Si hay un error en la ejecución
                $stmt->close();
                return "error en la consulta";
            }
        }

        // Si la preparación de la consulta falla, devolver false
        return "error en la preparacion de la consulta";
    }

    private function guardarFotoDePerfil($foto_perfil)
    {
    }

}