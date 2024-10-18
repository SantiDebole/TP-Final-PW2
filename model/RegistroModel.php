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
        $this->validarUsuarioYEmail($datos_usuario);

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

    private function validarUsuarioYEmail($datos_usuario)
    {


        $datos_usuario['email'];
        $datos_usuario['usuario'];

        $sql="SELECT 1 FROM usuario WHERE email=? OR usuario=?";
        $stmt = $this->database->connection->prepare($sql);

        // Verificar si la preparación fue exitosa
        if ($stmt) {
            // Enlazar parámetros (s: string, d: double, i: integer, b: blob)
            $hashed_password = password_hash($datos_usuario['password'], PASSWORD_DEFAULT);
            $stmt->bind_param("ss",
                $datos_usuario['email'],
                $datos_usuario['usuario']
            );
            }

        if ($stmt->execute()) {

            $registro="exitoso";


        } else {
            $registro="fallo";


        }
        $stmt->close();
        return $registro;


}}