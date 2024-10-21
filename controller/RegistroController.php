<?php
class RegistroController{

    private $model;

    private $presenter;

    public function __construct($model,$presenter){

        $this->model = $model;
        $this->presenter = $presenter;
    }

    public function listar(){

        $datos=[];
        $data = $this->cargaDeDatosParaRenderizacionDeFormulario($datos);

        $this->presenter->show('registro',$data);
    }

    public function validarRegistro(){
        //validar semantica del formulario
        $nombre_completo= $_POST['nombre_completo'];
        $fecha_nacimiento= $_POST['fecha_nacimiento'];
        $genero= isset($_POST['genero'])?$_POST['genero']:'';
        $email= $_POST['email'];
        $usuario= $_POST['usuario'];
        $password= $_POST['password'];
        $rol = "ur";
        $repeat_password =$_POST['repeat_password'];
        $foto_perfil = isset($_FILES['foto_perfil'])?$_FILES['foto_perfil']:$_POST['foto_perfil'];
        $pais = $_POST['pais'];
        $ciudad = $_POST['ciudad'];

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
            'ciudad' => $ciudad
        ];



        $data = $this->model->registrar($datos_usuario);
        /*var_dump($data);
        var_dump($data['errores']);*/
        if(empty($data['errores'])){
            $retorno['registro']=$data['nombreArchivo'];
            $this->presenter->show('validarEmail',$retorno);
        }else{

            $datos_usuario['errores']= $data['errores'];
            $data = $this->cargaDeDatosParaRenderizacionDeFormulario($datos_usuario);
            $this->presenter->show('registro',$data);
        }



    }
    public function validarEmail(){
      if(isset($_POST['hash'])){
        $rutaArchivo='./data/'.$_POST['hash'].'.json';
             if(file_exists($rutaArchivo)){ $data['registro']=$this->model->emailConfirmacion($rutaArchivo);
               } else $data['registro']="error en la confirmacion";
                } else $data['registro']="error en la confirmacion";

        $this->presenter->show('registroFinalizado', $data);

    }
    private function cargaDeDatosParaRenderizacionDeFormulario ($data)
    {
        $datos = [
            'nombre_completo' => '',
            'fecha_nacimiento' => '',
            'genero' => '',
            'email' => '',
            'usuario' => '',
            'pais' => '',
            'ciudad' => '',
            'errores' => [] // Inicialmente no hay errores
        ];
        if(isset($data['nombre_completo']))$datos['nombre_completo']=$data['nombre_completo'];
        if(isset($data['fecha_nacimiento']))$datos['fecha_nacimiento']=$data['fecha_nacimiento'];
        if(isset($data['genero']))$datos['genero']=$data['genero'];
        if(isset($data['email']))$datos['email']=$data['email'];
        if(isset($data['usuario']))$datos['usuario']=$data['usuario'];
        if(isset($data['pais']))$datos['pais']=$data['pais'];
        if(isset($data['ciudad']))$datos['ciudad']=$data['ciudad'];
        if(isset($data['nombre_completo']))$datos['nombre_completo']=$data['nombre_completo'];

        if (isset($data['errores']) && is_array($data['errores'])) {
            $datos['errores'] = array_merge($datos['errores'], $data['errores']);
        }
        var_dump($datos);
return $datos;
    }

}

