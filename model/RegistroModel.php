<?php

class RegistroModel
{

    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function ingresoPorEmail($token){

    $resultado="Error, la activacion no se ha podido realizar";
    $idUser="";
        $sql = "SELECT id FROM usuario WHERE token_verificacion = ? and esta_verificado=0";
    $stmt = $this->database->connection->prepare($sql);
    if($stmt){
        $stmt->bind_param("s", $token);
        if($stmt->execute()){
            $stmt->store_result();
            $stmt->bind_result($idUser);
            $stmt->fetch();
            }
        $stmt->close();
    }
    if($idUser!="") {
        $sql = "UPDATE usuario
                set esta_verificado =1
                where id = ?";
        $stmt = $this->database->connection->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("s", $idUser);
            $stmt->execute();
            if ($stmt->affected_rows > 0) $resultado = "La activacion se ha realizado con exito";
        }
        $stmt->close();


    } return $resultado;}
    public function reenviarEmail($email)
    {

        $token="";
        $sql = "SELECT token_verificacion FROM usuario WHERE email = ? and esta_verificado =0";
        $stmt = $this->database->connection->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("s", $email);
            if ($stmt->execute()) {
                $stmt->store_result();
                $stmt->bind_result($token);
                $stmt->fetch();
                $stmt->close();
                $mailer = new Mailer($email, $token);
                return $token;

            } else {
                $stmt->close();
                return "error en la consulta";
            }

        }


    }


    public function registrar($datos_usuario)
    {
        $errores=0;
        $datos_usuario['errores'] = [];
        $datos_usuario['nombreArchivo'] = [];

        //Valida las contraseñas, el email y el usuario
        list($errores, $datos_usuario) = $this->validarDatos($datos_usuario, $errores);
        if($errores==1) return $datos_usuario;

        //si el guardado de foto falla, devuelve un error sino devuelve el nombre de la imagen para guardarla en la bd
        $guardadoDeFotoDePerfil = $this->guardarFotoDePerfil($datos_usuario['foto_perfil']);

         $token=$this->cargarNuevoUsuarioEnBaseDeDatos($datos_usuario);
        if($token=="fallo"){
            $datos_usuario['errores'][] = "Error en la carga de base de datos";
            return $datos_usuario;}
        else {
            $mailer = new Mailer($datos_usuario['email'], $token);
            $datos_usuario['nombreArchivo']=$token;
            return $datos_usuario;
        }


    }
    private function cargarNuevoUsuarioEnBaseDeDatos($datos_usuario){
    $token = bin2hex(random_bytes(16));
    $sql = "INSERT INTO usuario (nombre_completo, fecha_nacimiento, genero, email, usuario, password, rol, foto_perfil, pais, ciudad, token_verificacion) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $this->database->connection->prepare($sql);
    if($stmt){
        $stmt->bind_param("sssssssssss",
            $datos_usuario['nombre_completo'],
            $datos_usuario['fecha_nacimiento'],
            $datos_usuario['genero'],
            $datos_usuario['email'],
            $datos_usuario['usuario'],
            $datos_usuario['password'],
            $datos_usuario['rol'],
            $datos_usuario['foto_perfil']['name'],
            $datos_usuario['pais'],
            $datos_usuario['ciudad'],
            $token
        );

        if ($stmt->execute()) {

            $registro = $token;


        } else {
            $registro = "fallo";


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

        if($password!='' &&strcmp($password, $repeat_password) == 0) {
            return "password valida";
        }else{
            return "password invalida";
        }

    }


    /**
     * @param $datos_usuario
     * @param int $errores
     * @return array
     */
    private function validarDatos($datos_usuario, int $errores): array
    {
        $validacionPasswordSeanIguales = $this->validarPassword($datos_usuario['password'], $datos_usuario['repeat_password']);
        if (strcmp($validacionPasswordSeanIguales, "password invalida") == 0) {
            $errores = 1;
            $datos_usuario['errores'][] = "Elija contraseña correctamente"; // Agrega el mensaje de error
        }

// Validar si el usuario ya existe
        $validacionUsuario = $this->validarUsuario($datos_usuario['usuario']);
        if ($validacionUsuario) {
            $errores = 1;
            $datos_usuario['errores'][] = "Usuario ya existente"; // Agrega el mensaje de error
        }


        $validacionEmail = $this->validarEmail($datos_usuario['email']);
        if ($validacionEmail) {
            $errores = 1;
            $datos_usuario['errores'][] = "Email ya existente"; // Agrega el mensaje de error
        }
        return array($errores, $datos_usuario);
    }
}