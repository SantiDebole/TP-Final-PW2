<?php

class AdministradorController
{
    private $model;

    private $presenter;

    public function __construct($model, $presenter)
    {

        $this->model = $model;
        $this->presenter = $presenter;
    }

    public function imprimirCantidadJugadores() {
        ob_clean();
        // Obtener los parámetros de la URL
        $filtro = $_GET['filtro'] ?? 'dia';
        $fecha_actual = $_GET['fecha'] ?? date('Y-m-d');
        $resultado = $_GET['resultado'] ?? 0;

        // Crear una nueva instancia de FPDF
        $pdf = new FPDF();
        $pdf->AddPage();

        // Establecer fuente para el título
        $pdf->SetFont('Arial', 'B', 16);

        // Agregar título al PDF con los datos recibidos
        $pdf->Cell(200, 10, "La cantidad de jugadores por $filtro en la fecha $fecha_actual es: $resultado", 0, 1, 'C');

        // Establecer fuente para el contenido
        $pdf->SetFont('Arial', '', 12);

        // Agregar un poco de espacio antes de los botones
        $pdf->Ln(10);

             // Generar y mostrar el PDF
        $pdf->Output('I', 'reporte.pdf'); // 'I' lo muestra en el navegador

        ob_end_flush();
    }


    public function dashboard(){
        $this->presenter->show('dashboard');
    }

    public function cantidadJugadores(){
        $this->presenter->show('formularioCantidadJugadores');
    }

    public function cantidadPartidasJugadas(){
        $this->presenter->show('formularioPartidasJugadas');
    }

    public function cantidadPreguntasEnElJuego(){
        $this->presenter->show('formularioCantidadPreguntasEnElJuego');
    }

    public function verCantidadPreguntasEnElJuego()
    {
        $filtro = $_POST['filtro'] ?? 'dia';
        $fecha_actual = $_POST['fecha'] ?? date('Y-m-d');

        switch ($filtro) {
            case 'dia':
                $rango = "DATE(fecha) = '$fecha_actual'";
                break;
            case 'semana':
                $rango = "YEARWEEK(fecha) = YEARWEEK('$fecha_actual')";
                break;
            case 'mes':
                $rango = "MONTH(fecha) = MONTH('$fecha_actual') AND YEAR(fecha) = YEAR('$fecha_actual')";
                break;
            case 'anio':
                $rango = "YEAR(fecha) = YEAR('$fecha_actual')";
                break;
            default:
                $rango = "1 = 1"; // Sin filtro
        }

        $resultado = $this->model->verCantidadPreguntasEnElJuego($rango);

        $data = [
            'resultado' => $resultado,
            'filtro' => $filtro,
            'fecha' => $fecha_actual,
        ];

        $this->presenter->show('verCantidadPreguntasEnElJuego', $data);
    }

    public function verCantidadPartidasJugadas(){
        $filtro = $_POST['filtro'] ?? 'dia';
        $fecha_actual = $_POST['fecha'] ?? date('Y-m-d');

        switch ($filtro) {
            case 'dia':
                $rango = "DATE(fecha) = '$fecha_actual'";
                break;
            case 'semana':
                $rango = "YEARWEEK(fecha) = YEARWEEK('$fecha_actual')";
                break;
            case 'mes':
                $rango = "MONTH(fecha) = MONTH('$fecha_actual') AND YEAR(fecha) = YEAR('$fecha_actual')";
                break;
            case 'anio':
                $rango = "YEAR(fecha) = YEAR('$fecha_actual')";
                break;
            default:
                $rango = "1 = 1"; // Sin filtro
        }

        $resultado = $this->model->verCantidadPartidasJugadas($rango);

        $data = [
            'resultado' => $resultado,
            'filtro' => $filtro,
            'fecha' => $fecha_actual,
        ];

        $this->presenter->show('verCantidadPartidasJugadas', $data);
    }

    public function verCantidadJugadores(){
        $filtro = $_POST['filtro'] ?? 'dia';
        $fecha_actual = $_POST['fecha'] ?? date('Y-m-d');

        switch ($filtro) {
            case 'dia':
                $rango = "DATE(fecha_creacion) = '$fecha_actual'";
                break;
            case 'semana':
                $rango = "YEARWEEK(fecha_creacion) = YEARWEEK('$fecha_actual')";
                break;
            case 'mes':
                $rango = "MONTH(fecha_creacion) = MONTH('$fecha_actual') AND YEAR(fecha_creacion) = YEAR('$fecha_actual')";
                break;
            case 'anio':
                $rango = "YEAR(fecha_creacion) = YEAR('$fecha_actual')";
                break;
            default:
                $rango = "1 = 1"; // Sin filtro
        }

        $resultado = $this->model->verCantidadJugadores($rango);

        $data = [
            'resultado' => $resultado,
            'filtro' => $filtro,
            'fecha' => $fecha_actual,
        ];

        $this->presenter->show('verCantidadJugadores', $data);
    }


}