<?php

// Requiere los archivos de JpGraph
require_once('vendor/jpgraph-4.4.2/src/jpgraph.php');
require_once('vendor/jpgraph-4.4.2/src/jpgraph_bar.php');
require_once('vendor/jpgraph-4.4.2/src/jpgraph_line.php');

require_once('vendor/jpgraph-4.4.2/src/jpgraph.php');
require_once('vendor/jpgraph-4.4.2/src/jpgraph_bar.php');
require_once('vendor/jpgraph-4.4.2/src/jpgraph_line.php');
require_once('vendor/jpgraph-4.4.2/src/jpgraph_pie.php'); // Incluir para gráficos de torta


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

    public function generatePieGraph($data, $labels, $title, $outputFile) {
        // Limpiar el buffer de salida
        ob_clean();
        flush();

        try {
            // Crear el gráfico de torta
            $graph = new PieGraph(800, 600); // Dimensiones del gráfico
            $graph->title->Set($title); // Establecer el título

            // Crear el gráfico de torta
            $piePlot = new PiePlot($data);
            $piePlot->SetLegends($labels); // Etiquetas para las secciones
            $piePlot->SetLabelType(PIE_VALUE_PER); // Mostrar porcentajes
            $piePlot->value->SetFormat('%2.1f%%'); // Formato de los valores
            $piePlot->value->SetFont(FF_FONT1, FS_NORMAL);

            // Añadir el gráfico al objeto
            $graph->Add($piePlot);

            // Guardar el gráfico como archivo
            $graph->Stroke($outputFile);
        } catch (Exception $e) {
            // Mostrar el error
            echo "Error generando el gráfico de torta: " . $e->getMessage();
        }
    }



}
