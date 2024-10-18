<?php
class RegistroController{

    private $model;

    private $presenter;

    public function __construct($model,$presenter){

        $this->model = $model;
        $this->presenter = $presenter;
    }

    public function listar(){

        $data = [];

        $this->presenter->show('registro',$data);
    }

    public function validarRegistro(){
        //validar semantica del formulario
        $nombre_completo= $_POST['nombre_completo'];
        $fecha_nacimiento= $_POST['fecha_nacimiento'];
        $genero= $_POST['genero'];
        $email= $_POST['email'];
        $usuario= $_POST['usuario'];
        $password= $_POST['password'];
        $rol = "ur";
        $repeat_password =$_POST['repeat_password'];
        $foto_perfil = isset($_POST['foto_perfil'])?$_POST['foto_perfil']:'';
        $pais = $_POST['pais'];
        $ciudad = $_POST['ciudad'];

        // Var_dump prolijo
        echo "<pre>";
        echo "Datos recibidos del formulario:\n";
        echo "Nombre completo: ";
        var_dump($nombre_completo);
        echo "\nFecha de nacimiento: ";
        var_dump($fecha_nacimiento);
        echo "\nGénero: ";
        var_dump($genero);
        echo "\nEmail: ";
        var_dump($email);
        echo "\nUsuario: ";
        var_dump($usuario);
        echo "\nContraseña: ";
        var_dump($password);
        echo "\nRepetir contraseña: ";
        var_dump($repeat_password);
        echo "\nFoto de perfil: ";
        var_dump($foto_perfil);  // Archivo subido
        echo "\nPaís: ";
        var_dump($pais);
        echo "\nCiudad: ";
        var_dump($ciudad);
        echo "</pre>";

        $datos_usuario = [
            'nombre_completo' => $nombre_completo,
            'fecha_nacimiento' => $fecha_nacimiento,
            'genero' => $genero,
            'email' => $email,
            'usuario' => $usuario,
            'password' => $password,
            'rol' => $rol,
            'repeat_password' => $repeat_password,
            'foto_perfil' => $foto_perfil,
            'pais' => $pais,
            'ciudad' => $ciudad,
        ];



        $data['registro'] = $this->model->registrar($datos_usuario);


        $this->presenter->show('registro',$data);
    }



}