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
    $usuario="";
        $sql = "SELECT id, usuario FROM usuario WHERE token_verificacion = ? and esta_verificado=0";
    $result = $this->database->executeQueryConParametros($sql,[$token]);
    $resultado = $result->fetch_assoc();
    $idUser = $resultado["id"];
    $usuario = $resultado["usuario"];
    if($idUser!="") {
        $sql = "UPDATE usuario
                set esta_verificado =1
                where id = ?";
        $affected_rows = $this->database->executeQueryConParametros($sql,[$idUser]);
            if ($affected_rows > 0) {
                $resultado = "La activacion se ha realizado con exito";
                $generadorDeQR = new GeneradorDeQR();
                $generadorDeQR->generarQRParaPerfil($usuario);
            }
    }
    return $resultado;}
    public function reenviarEmail($email)
    {

        $token="";
        $sql = "SELECT token_verificacion FROM usuario WHERE email = ? and esta_verificado =0";
        $result = $this->database->executeQueryConParametros($sql,[$email]);
        if ($result) {
                $mailer = new Mailer($email, $token);
            $mailer->mandarEmail($gmail, $token);
                return $token;
        }
        return new Exception("Error, no se pudo enviar el correo");
    }


    public function registrar($datos_usuario)
    {
        $errores=0;
        $datos_usuario['errores'] = [];
        $datos_usuario['nombreArchivo'] = [];


        list($errores, $datos_usuario) = $this->validarDatos($datos_usuario, $errores);
        if($errores==1) return $datos_usuario;

        //si el guardado de foto falla, devuelve un error sino devuelve el nombre de la imagen para guardarla en la bd
        $guardadoDeFotoDePerfil = $this->guardarFotoDePerfil($datos_usuario['foto_perfil']);
        if($guardadoDeFotoDePerfil==0){
            $datos_usuario['errores'][] = "Error en la carga de la foto";
            return $datos_usuario;
        }

         $token=$this->cargarNuevoUsuarioEnBaseDeDatos($datos_usuario);
        if($token=="fallo"){
            $datos_usuario['errores'][] = "Error en la carga de base de datos";
            return $datos_usuario;}
        else {
            $mailer = new Mailer();
            $resultadoEmail = $mailer->mandarEmail($datos_usuario['email'], $token);
            if($resultadoEmail==0) {
                $datos_usuario['errores'][] = "Error en el envio de email";
                $this->borrarDatosPorErrorEnElEnvioDelEmail($datos_usuario['usuario']);
                return $datos_usuario;
            }

            $datos_usuario['nombreArchivo']=$token;
            return $datos_usuario;
        }


    }
    private function cargarNuevoUsuarioEnBaseDeDatos($datos_usuario){
    $token = bin2hex(random_bytes(16));
    $sql = "INSERT INTO usuario (nombre_completo, fecha_nacimiento, genero, email, usuario, password, rol, foto_perfil, pais, ciudad, token_verificacion) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $affected_rows = $this->database->executeQueryConParametros($sql,[$datos_usuario['nombre_completo'],
        $datos_usuario['fecha_nacimiento'],
        $datos_usuario['genero'],
        $datos_usuario['email'],
        $datos_usuario['usuario'],
        $datos_usuario['password'],
        $datos_usuario['rol'],
        $datos_usuario['foto_perfil']['name'],
        $datos_usuario['pais'],
        $datos_usuario['ciudad'],
        $token]);
    if($affected_rows > 0){
        return $token;
    }
    return "fallo";
    }

       private function validarUsuario($usuario)
    {
        $sql = "SELECT 1 FROM usuario WHERE usuario=?";
        $result = $this->database->executeQueryConParametros($sql,[$usuario]);
        // Verificar si la preparación fue exitosa
        if ($result) {
                // Verificar si existe al menos una fila
                if ($result->num_rows > 0) {
                    // Si hay una fila, el usuario ya existe
                    return true;
                } else {
                    // Si no hay filas, el usuario no existe
                    return false;
                }
        }
        // Si la preparación de la consulta falla, devolver false
        return "error en la preparacion de la consulta";
    }

    private function validarEmail($email)
    {
        $sql = "SELECT 1 FROM usuario WHERE email=?";
        $result = $this->database->executeQueryConParametros($sql,[$email]);
        // Verificar si la preparación fue exitosa
        if ($result) {
                if ($result->num_rows > 0) {
                    // Si hay una fila, el usuario ya existe
                    return true;
                } else {
                    // Si no hay filas, el usuario no existe
                    return false;
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
            $extensionArchivo = pathinfo($nombreArchivo, PATHINFO_EXTENSION);
            if ($tamanioArchivo > 2097152) {
                return 0;
            }
            $carpetaDestino = 'public/image/';
            $nombreImagen = pathinfo($foto_perfil["name"],PATHINFO_FILENAME); // Hago que el nombre de la imagen sea igual al nombre del pokemon, para despues mostrarlo bien en la vista principal
            $rutaImagen = $carpetaDestino . $nombreImagen . '.' . $extensionArchivo;
            if (!move_uploaded_file($archivoTemporal, $rutaImagen)) {
                return 0;
            }
        }
    return 1;
    }

    private function validarPassword($password, $repeat_password)

    {

        if($password!='' &&strcmp($password, $repeat_password) == 0) {
            return "password valida";
        }else{
            return "password invalida";
        }

    }


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

    private function borrarDatosPorErrorEnElEnvioDelEmail($usuario){
    $sql = "DELETE FROM usuario WHERE usuario = ?";
    $this->database->executeQueryConParametros($sql,[$usuario]);
}
}