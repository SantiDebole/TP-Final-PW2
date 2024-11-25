<?php
class Router
{

    private $defaultController;
    private $defaultMethod;
    private $configuration;

    public function __construct($configuration, $defaultController, $defaultMethod)
    {

        $this->configuration = $configuration;
        $this->defaultController = $defaultController;
        $this->defaultMethod = $defaultMethod;

    }

    public function route($controllerName, $methodName, $param)
    {

        $this->controlarRutas($controllerName, $methodName);
        $controller = $this->getControllerFrom($controllerName);
        $this->executeMethodFromController($controller, $methodName, $param);

    }

    private function getControllerFrom($module)
    {
        $controllerName = 'get' . ucfirst($module) . 'Controller';
        $validController = method_exists($this->configuration, $controllerName) ? $controllerName : $this->defaultController;
        return call_user_func(array($this->configuration, $validController));
    }

    private function executeMethodFromController($controller, $method, $param)
    {
        $params = array_slice(func_get_args(), 2);
        $validMethod = method_exists($controller, $method) ? $method : $this->defaultMethod;
        call_user_func(array($controller, $validMethod), $param);
    }

    private function controlarRutas($controllerName, $methodName)
    {

        $rutasPermitidas = [];
        $rutaConstruida = "$controllerName/$methodName";


        if (!isset($_SESSION['rol'])) {
            $rutasPermitidas = ['registro/listar',
                'registro/validarRegistro',
                'registro/reenviarEmail',
                'registro/ingresoPorEmail',
                'login/listar',
                'login/validate'
            ];
        } else {
            switch ($_SESSION['rol']) {
                case "ur":
                    $rutasPermitidas = [ 'lobby/listar',
                        'lobby/verRival',
                        'lobby/verRivalPorQR',
                        'lobby/mis_partidas',
                        'lobby/ranking',
                        'partida/mostrarPuntos',
                        'partida/iniciarPartida',
                        'partida/jugarNuevaPartida',
                        'partida/crearPartida',
                        'partida/preguntar',
                        'partida/reanudarPartida',
                        'partida/responder',
                        'login/logout',
                        'login/validate',
                        'perfil/listar',
                        'reporte/reporteEnviado',
                        'reporte/reportarPregunta',
                        'reporte/reporteEnviadoCorrecta',
                        'reporte/reporteEnviadoIncorrecta',
                        'pregunta/sugerirPregunta',
                        'pregunta/validarPreguntaSugerida'];
                    break;
                case "e":
                    $rutasPermitidas = [
                        'lobby/listar',
                        'login/validate',
                        'login/logout',
                        'editor/preguntasReportadas',
                        'perfil/listar',
                        'editorController/manejoAccionReporte',
                        'editor/modificarPreguntaYORespuestas',
                        'editor/mostrarFormularioEdicionPregunta',
                        'editor/verReportes',
                        'editor/preguntasSugeridas',
                        'editor/sugerirPregunta'
                    ];
                    break;

                case "a":
                    $rutasPermitidas = [ 'lobby/listar',
                                         'login/validate',
                                         'login/logout',
                                         'perfil/listar',
                                         'administrador/dashboard',
                                            'administrador/cantidadJugadores',
                                            'administrador/verCantidadJugadores',
                                                'administrador/cantidadPartidasJugadas',
                                                'administrador/verCantidadPartidasJugadas',
                                                    'administrador/cantidadPreguntasEnElJuego',
                                                    'administrador/verCantidadPreguntasEnElJuego',
                                                        'administrador/imprimirCantidadJugadores',
                                                        'administrador/imprimirCantidadPartidasJugadas',
                                                        'administrador/imprimirCantidadPreguntas',
                                                        'administrador/verCantidadPreguntasRespondidasCorrectamente',
                                                        'administrador/cantidadPreguntasRespondidasCorrectamente',
                                                        'administrador/imprimirCantidadPreguntasRespondidasCorrectamente'];
                                         break;
            }


        }
        $this->controlDeRuta($rutaConstruida, $rutasPermitidas);

    }

    private function controlDeRuta($rutaConstruida, $rutasPermitidas)
    {

        foreach ($rutasPermitidas as $rutaPermitida) {
            if ($rutaPermitida == $rutaConstruida) {
                return true;
            }

        }

        if(isset($_SESSION['rol'])) {
            header("Location: /lobby/listar");
        exit();
        }
        else {header("Location: /login/listar");
            exit();}

    }


}