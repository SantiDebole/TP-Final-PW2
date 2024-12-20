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
        $uniqueId = $_GET['uniqueId'] ?? 0;

        $grafico = $_SERVER['DOCUMENT_ROOT'] . '/public/image/grafico_' . $filtro . '_' . $fecha_actual ."_".$uniqueId.'.png';
        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->MultiCell(0, 10, "La cantidad de jugadores por $filtro en la fecha '$fecha_actual' es: $resultado", 0, 'C');
        $pdf->Ln(10);
        if (file_exists($grafico)) {
            $pdf->Image($grafico, 50, 60, 100, 70);
        } else {
            $pdf->SetFont('Arial', 'I', 12);
            $pdf->Cell(0, 10, "El gráfico no está disponible.", 0, 1, 'C');
        }
        $pdf->Output('I', 'reporte.pdf'); // 'I' lo muestra en el navegador
        ob_end_flush();
    }

    public function imprimirCantidadPartidasJugadas() {
        ob_clean();

        $filtro = $_GET['filtro'] ?? 'dia';
        $fecha_actual = $_GET['fecha'] ?? date('Y-m-d');
        $resultado = $_GET['resultado'] ?? 0;
        $uniqueId = $_GET['uniqueId'] ?? 0;


        $grafico = $_SERVER['DOCUMENT_ROOT'] . '/public/image/grafico_' . $filtro . '_' . $fecha_actual ."_".$uniqueId.'.png';

        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->MultiCell(0, 10, "La cantidad de jugadores por $filtro en la fecha '$fecha_actual' es: $resultado", 0, 'C');
        $pdf->Ln(10);
        if (file_exists($grafico)) {
            $pdf->Image($grafico, 50, 60, 100, 70);
        } else {
            $pdf->SetFont('Arial', 'I', 12);
            $pdf->Cell(0, 10, "El gráfico no está disponible.", 0, 1, 'C');
        }
        $pdf->Output('I', 'reporte.pdf'); // 'I' lo muestra en el navegador
        ob_end_flush();
    }

    public function imprimirCantidadPreguntas() {
        ob_clean();
        $filtro = $_GET['filtro'] ?? 'dia';
        $fecha_actual = $_GET['fecha'] ?? date('Y-m-d');
        $resultado = $_GET['resultado'] ?? 0;
        $uniqueId = $_GET['uniqueId'] ?? 0;
        $grafico = $_SERVER['DOCUMENT_ROOT'] . '/public/image/grafico_' . $filtro . '_' . $fecha_actual ."_".$uniqueId.'.png';
        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->MultiCell(0, 10, "La cantidad de jugadores por $filtro en la fecha '$fecha_actual' es: $resultado", 0, 'C');
        $pdf->Ln(10);
        if (file_exists($grafico)) {
            $pdf->Image($grafico, 50, 60, 100, 70);
        } else {
            $pdf->SetFont('Arial', 'I', 12);
            $pdf->Cell(0, 10, "El gráfico no está disponible.", 0, 1, 'C');
        }
        $pdf->Output('I', 'reporte.pdf'); // 'I' lo muestra en el navegador
        ob_end_flush();
    }

    public function imprimirCantidadPreguntasRespondidasCorrectamente(){
        ob_clean();
        $filtro = $_GET['filtro'] ?? 'dia';
        $fecha_actual = $_GET['fecha'] ?? date('Y-m-d');
        $resultadoPreguntasRespondidasCorrectamente =   $_GET['resultadoPreguntasRespondidasCorrectamente'] ?? 0;
        $resultadoPreguntasRespondidasIncorrectamente = $_GET['resultadoPreguntasRespondidasIncorrectamente'] ?? 0;
        $uniqueId = $_GET['uniqueId'] ?? 0;
        $grafico = $_SERVER['DOCUMENT_ROOT'] . '/public/image/grafico_' . $filtro . '_' . $fecha_actual ."_".$uniqueId.'.png';
        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->MultiCell(0, 10, "La cantidad de preguntas respondidas correctamente por $filtro en la fecha '$fecha_actual' es: $resultadoPreguntasRespondidasCorrectamente", 0, 'C');
        $pdf->MultiCell(0, 10, "La cantidad de preguntas respondidas incorrectamente por $filtro en la fecha '$fecha_actual' es: $resultadoPreguntasRespondidasIncorrectamente", 0, 'C');
        $pdf->Ln(10);
        if (file_exists($grafico)) {
            $pdf->Image($grafico, 50, 60, 100, 70);
        } else {
            $pdf->SetFont('Arial', 'I', 12);
            $pdf->Cell(0, 10, "El gráfico no está disponible.", 0, 1, 'C');
        }
        $pdf->Output('I', 'reporte.pdf'); // 'I' lo muestra en el navegador
        ob_end_flush();

    }

    public function dashboard(){
        $this->presenter->show('dashboard');
    }

    public function cantidadJugadores(){
        $this->presenter->show('formularioCantidadJugadores');
    }

    public function cantidadPreguntasRespondidasCorrectamente(){
        $this->presenter->show('formularioPreguntasRespondidasCorrectamente');
    }

    public function cantidadPartidasJugadas(){
        $this->presenter->show('formularioPartidasJugadas');
    }

    public function cantidadPreguntasEnElJuego(){
        $this->presenter->show('formularioCantidadPreguntasEnElJuego');
    }

    public function verCantidadPreguntasRespondidasCorrectamente(){

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

        $resultadoPreguntasRespondidasCorrectamente = $this->model->verCantidadPreguntasRespondidasCorrectamenteEnElJuego($rango);
        $resultadoPreguntasHechas = $this->model->verCantidadPreguntasHechasEnElJuego($rango);

        // Calcular incorrectas
        $preguntasIncorrectas = $resultadoPreguntasHechas - $resultadoPreguntasRespondidasCorrectamente;

        // Datos para el gráfico
        $data = [$resultadoPreguntasRespondidasCorrectamente, $preguntasIncorrectas];
        $labels = ['Correctas', 'Incorrectas'];

        $graphHelper = new GraphHelper();
        $uniqueId = uniqid();
        $nombreGrafico = "grafico_" . $filtro . "_" . $fecha_actual ."_".$uniqueId. ".png";
        $outputFile = $_SERVER['DOCUMENT_ROOT'] . '/public/image/' . $nombreGrafico;
        $graphHelper->generatePieGraph($data, $labels, "Preguntas Respondidas Correctamente", $outputFile);
        $ruta = '/public/image/' . $nombreGrafico;



        $data = [
            'resultadoPreguntasRespondidasCorrectamente' => $resultadoPreguntasRespondidasCorrectamente,
            'resultadoPreguntasHechas' => $resultadoPreguntasHechas,
            'resultadoPreguntasIncorrectas' => $preguntasIncorrectas,
            'filtro' => $filtro,
            'fecha' => $fecha_actual,
            'grafico' => $ruta,
            'uniqueId' => $uniqueId
        ];

        $this->presenter->show('verCantidadPreguntasRespondidasCorrectamenteEnElJuego', $data);

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


        $graphHelper = new GraphHelper();
        $labels = [$fecha_actual];
        $data = [$resultado];     // Datos obtenidos de la consulta
        $title = "Cantidad de Jugadores por $filtro";
        $uniqueId = uniqid();
        $nombreGrafico = "grafico_" . $filtro . "_" . $fecha_actual ."_".$uniqueId. ".png";
        $outputFile = $_SERVER['DOCUMENT_ROOT'] . '/public/image/' . $nombreGrafico;
        $ruta = '/public/image/' . $nombreGrafico;

        $graphHelper->generateBarGraph($data, $labels, $title, $outputFile);

        $data = [
            'resultado' => $resultado,
            'filtro' => $filtro,
            'fecha' => $fecha_actual,
            'grafico' => $ruta,
            'uniqueId' => $uniqueId
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
        $graphHelper = new GraphHelper();
        $labels = [$fecha_actual];
        $data = [$resultado];
        $title = "Cantidad de Jugadores por $filtro";
        $uniqueId = uniqid();
        $nombreGrafico = "grafico_" . $filtro . "_" . $fecha_actual ."_".$uniqueId. ".png";
        $outputFile = $_SERVER['DOCUMENT_ROOT'] . '/public/image/' . $nombreGrafico;
        $ruta = '/public/image/' . $nombreGrafico;

        $graphHelper->generateBarGraph($data, $labels, $title, $outputFile);


        $data = [
            'resultado' => $resultado,
            'filtro' => $filtro,
            'fecha' => $fecha_actual,
            'grafico' => $ruta,
            'uniqueId' => $uniqueId
        ];

        $this->presenter->show('verCantidadPartidasJugadas', $data);
    }

    public function verCantidadJugadores() {
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

        $graphHelper = new GraphHelper();
        $labels = [$fecha_actual];
        $data = [$resultado];
        $title = "Cantidad de Jugadores por $filtro";
        $uniqueId = uniqid();
        $nombreGrafico = "grafico_" . $filtro . "_" . $fecha_actual ."_".$uniqueId. ".png";
        $outputFile = $_SERVER['DOCUMENT_ROOT'] . '/public/image/' . $nombreGrafico;
        $ruta = '/public/image/' . $nombreGrafico;

        $graphHelper->generateBarGraph($data, $labels, $title, $outputFile);

        /*if (!$graphHelper->generateBarGraph($data, $labels, $title, $outputFile)) {
            // Manejar error aquí, por ejemplo, logueando el error o mostrando un mensaje al usuario.
            throw new Exception("Error al generar el gráfico.");
        }*/


        $data = [
            'resultado' => $resultado,
            'filtro' => $filtro,
            'fecha' => $fecha_actual,
            'grafico' => $ruta,
            'uniqueId' => $uniqueId
        ];

        $this->presenter->show('verCantidadJugadores', $data);
    }




}