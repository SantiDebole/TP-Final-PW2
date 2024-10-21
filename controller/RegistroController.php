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
        $datos = [];

        $campos = [
            'nombre_completo',
            'fecha_nacimiento',
            'genero',
            'email',
            'usuario',
            'pais',
            'ciudad',
            'errores'
        ];

        foreach ($campos as $campo) {
            if (isset($data[$campo])) {
                $datos[$campo] = $data[$campo];
            }else $datos[$campo]='';
        }

        var_dump($datos);
return $datos;
    }

}

