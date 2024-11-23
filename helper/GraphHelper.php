<?php

// Requiere los archivos de JpGraph
require_once('vendor/jpgraph-4.4.2/src/jpgraph.php');
require_once('vendor/jpgraph-4.4.2/src/jpgraph_bar.php');
require_once('vendor/jpgraph-4.4.2/src/jpgraph_line.php');

class GraphHelper {

    /**
     * Genera un gráfico de barras
     *
     * @param array $data Datos para las barras
     * @param array $labels Etiquetas para el eje X
     * @param string $title Título del gráfico
     * @param string $outputFile Ruta donde se guardará el gráfico como imagen
     */
    public function generateBarGraph($data, $labels, $title, $outputFile) {
        // Limpiar el buffer de salida
        ob_clean();
        flush();

        try {
            // Crear el gráfico
            $graph = new Graph(800, 600);
            $graph->SetScale("textlin");

            // Configurar el título y las etiquetas
            $graph->title->Set($title);
            $graph->xaxis->SetTickLabels($labels);

            // Crear las barras
            $barPlot = new BarPlot($data);
            $graph->Add($barPlot);

            // Guardar el gráfico como archivo
            $graph->Stroke($outputFile);
        } catch (Exception $e) {
            // Mostrar el error
            echo "Error generando el gráfico: " . $e->getMessage();
        }


    }


    /**
     * Genera un gráfico de líneas
     *
     * @param array $data Datos para las líneas
     * @param array $labels Etiquetas para el eje X
     * @param string $title Título del gráfico
     * @param string $outputFile Ruta donde se guardará el gráfico como imagen
     */
    public function generateLineGraph($data, $labels, $title, $outputFile) {
        // Crear el gráfico
        $graph = new Graph(800, 600);
        $graph->SetScale("textlin");

        // Configurar el título y las etiquetas
        $graph->title->Set($title);
        $graph->xaxis->SetTickLabels($labels);

        // Crear la línea
        $linePlot = new LinePlot($data);
        $graph->Add($linePlot);

        // Guardar el gráfico como archivo
        $graph->Stroke($outputFile);
    }
}
