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
        $validacionPasswordSeanIguales = $this->validarPassword($datos_usuario['password'], $datos_usuario['repeat_password']);
        if(strcmp($validacionPasswordSeanIguales, "password invalida")==0) {
            return "Las password no son iguales";
        }

        $validacionUsuario = $this->validarUsuario($datos_usuario['usuario']);
        if($validacionUsuario == true){
            return "Usuario ya existente";
        }
        $validacionEmail = $this->validarEmail($datos_usuario['email']);
        if($validacionEmail == true){
            return "Email ya existente";
        }

        //si el guardado de foto falla, devuelve un error sino devuelve el nombre de la imagen para guardarla en la bd
        $guardadoDeFotoDePerfil = $this->guardarFotoDePerfil($datos_usuario['foto_perfil']);
        //envia el mail

        $sql = "INSERT INTO usuario (nombre_completo, fecha_nacimiento, genero, email, usuario, password, rol, foto_perfil, pais, ciudad) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        // Preparar la consulta
        $stmt = $this->database->connection->prepare($sql);

        // Verificar si la preparación fue exitosa
        if ($stmt) {
            // Enlazar parámetros (s: string, d: double, i: integer, b: blob)
            //$hashed_password = password_hash($datos_usuario['password'], PASSWORD_DEFAULT);
            $stmt->bind_param("ssssssssss",
                $datos_usuario['nombre_completo'],
                $datos_usuario['fecha_nacimiento'],
                $datos_usuario['genero'],
                $datos_usuario['email'],
                $datos_usuario['usuario'],
                $datos_usuario['password'],
                $datos_usuario['rol'],
                $datos_usuario['foto_perfil']['name'],
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
        if (isset($foto_perfil) && $foto_perfil['error'] === UPLOAD_ERR_OK) {

            $nombreArchivo = $foto_perfil['name'];
            $tipoArchivo = $foto_perfil['type'];
            $tamanioArchivo = $foto_perfil['size'];
            $archivoTemporal = $foto_perfil['tmp_name'];


            // Si se sube un archivo que no sea png, me da error
            $extensionArchivo = pathinfo($nombreArchivo, PATHINFO_EXTENSION);


            // Limito el tamaño del archivo a 2mb
            if ($tamanioArchivo > 2097152) {
                echo "El archivo es muy pesado. Tamaño máximo permitido es 2MB.";
                exit();
            }

            $carpetaDestino = 'public/image/';
            $nombreImagen = pathinfo($foto_perfil["name"],PATHINFO_FILENAME); // Hago que el nombre de la imagen sea igual al nombre del pokemon, para despues mostrarlo bien en la vista principal

            $rutaImagen = $carpetaDestino . $nombreImagen . '.' . $extensionArchivo;

            // Mover el archivo desde su ubicación temporal a la carpeta de destino
            if (!move_uploaded_file($archivoTemporal, $rutaImagen)) {
                echo "Error al subir la imagen.";
                exit();
            }
        }

    }

    private function validarPassword($password, $repeat_password)

    {

        if(strcmp($password, $repeat_password) == 0) {
            return "password valida";
        }else{
            return "password invalida";
        }

    }

}