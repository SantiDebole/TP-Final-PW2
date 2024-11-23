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

        // Ruta del gráfico (debes asegurarte de pasar esta variable desde tu vista)
        $grafico = $_SERVER['DOCUMENT_ROOT'] . '/public/image/grafico_' . $filtro . '_' . $fecha_actual ."_".$uniqueId.'.png';

        // Crear una nueva instancia de FPDF
        $pdf = new FPDF();
        $pdf->AddPage();

        // Establecer fuente para el título
        $pdf->SetFont('Arial', 'B', 16);

        // Agregar título al PDF con los datos recibidos
        $pdf->MultiCell(0, 10, "La cantidad de jugadores por $filtro en la fecha '$fecha_actual' es: $resultado", 0, 'C');

        // Agregar un espacio antes de insertar la imagen
        $pdf->Ln(10);

        // Insertar la imagen del gráfico al PDF
        if (file_exists($grafico)) {
            // Insertar el gráfico con dimensiones ajustadas
            $pdf->Image($grafico, 50, 60, 100, 70); // Ajusta las coordenadas y dimensiones según sea necesario
        } else {
            // Mostrar un mensaje si no se encuentra el gráfico
            $pdf->SetFont('Arial', 'I', 12);
            $pdf->Cell(0, 10, "El gráfico no está disponible.", 0, 1, 'C');
        }

        // Generar y mostrar el PDF
        $pdf->Output('I', 'reporte.pdf'); // 'I' lo muestra en el navegador
        ob_end_flush();
    }

    public function imprimirCantidadPartidasJugadas() {
        ob_clean();
        // Obtener los parámetros de la URL
        $filtro = $_GET['filtro'] ?? 'dia';
        $fecha_actual = $_GET['fecha'] ?? date('Y-m-d');
        $resultado = $_GET['resultado'] ?? 0;
        $uniqueId = $_GET['uniqueId'] ?? 0;

        // Ruta del gráfico (debes asegurarte de pasar esta variable desde tu vista)
        $grafico = $_SERVER['DOCUMENT_ROOT'] . '/public/image/grafico_' . $filtro . '_' . $fecha_actual ."_".$uniqueId.'.png';

        // Crear una nueva instancia de FPDF
        $pdf = new FPDF();
        $pdf->AddPage();

        // Establecer fuente para el título
        $pdf->SetFont('Arial', 'B', 16);

        // Agregar título al PDF con los datos recibidos
        $pdf->MultiCell(0, 10, "La cantidad de jugadores por $filtro en la fecha '$fecha_actual' es: $resultado", 0, 'C');

        // Agregar un espacio antes de insertar la imagen
        $pdf->Ln(10);

        // Insertar la imagen del gráfico al PDF
        if (file_exists($grafico)) {
            // Insertar el gráfico con dimensiones ajustadas
            $pdf->Image($grafico, 50, 60, 100, 70); // Ajusta las coordenadas y dimensiones según sea necesario
        } else {
            // Mostrar un mensaje si no se encuentra el gráfico
            $pdf->SetFont('Arial', 'I', 12);
            $pdf->Cell(0, 10, "El gráfico no está disponible.", 0, 1, 'C');
        }

        // Generar y mostrar el PDF
        $pdf->Output('I', 'reporte.pdf'); // 'I' lo muestra en el navegador
        ob_end_flush();
    }

    public function imprimirCantidadPreguntas() {
        ob_clean();
        // Obtener los parámetros de la URL
        $filtro = $_GET['filtro'] ?? 'dia';
        $fecha_actual = $_GET['fecha'] ?? date('Y-m-d');
        $resultado = $_GET['resultado'] ?? 0;
        $uniqueId = $_GET['uniqueId'] ?? 0;

        // Ruta del gráfico (debes asegurarte de pasar esta variable desde tu vista)
        $grafico = $_SERVER['DOCUMENT_ROOT'] . '/public/image/grafico_' . $filtro . '_' . $fecha_actual ."_".$uniqueId.'.png';

        // Crear una nueva instancia de FPDF
        $pdf = new FPDF();
        $pdf->AddPage();

        // Establecer fuente para el título
        $pdf->SetFont('Arial', 'B', 16);

        // Agregar título al PDF con los datos recibidos
        $pdf->MultiCell(0, 10, "La cantidad de jugadores por $filtro en la fecha '$fecha_actual' es: $resultado", 0, 'C');

        // Agregar un espacio antes de insertar la imagen
        $pdf->Ln(10);

        // Insertar la imagen del gráfico al PDF
        if (file_exists($grafico)) {
            // Insertar el gráfico con dimensiones ajustadas
            $pdf->Image($grafico, 50, 60, 100, 70); // Ajusta las coordenadas y dimensiones según sea necesario
        } else {
            // Mostrar un mensaje si no se encuentra el gráfico
            $pdf->SetFont('Arial', 'I', 12);
            $pdf->Cell(0, 10, "El gráfico no está disponible.", 0, 1, 'C');
        }

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

        $graphHelper = new GraphHelper();
        $labels = [$fecha_actual]; // Puedes ajustar según lo que necesites como etiquetas
        $data = [$resultado];     // Datos obtenidos de la consulta
        $title = "Cantidad de Jugadores por $filtro";
        $uniqueId = uniqid();
        $nombreGrafico = "grafico_" . $filtro . "_" . $fecha_actual ."_".$uniqueId. ".png";
        $outputFile = $_SERVER['DOCUMENT_ROOT'] . '/public/image/' . $nombreGrafico;
        $ruta = '/public/image/' . $nombreGrafico;

        $graphHelper->generateBarGraph($data, $labels, $title, $outputFile);


        // Pasar datos y la ruta del gráfico a la vista
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
        $labels = [$fecha_actual]; // Puedes ajustar según lo que necesites como etiquetas
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

        // Usar GraphHelper para generar el gráfico
        $graphHelper = new GraphHelper();
        $labels = [$fecha_actual]; // Puedes ajustar según lo que necesites como etiquetas
        $data = [$resultado];     // Datos obtenidos de la consulta
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

        // Pasar datos y la ruta del gráfico a la vista
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