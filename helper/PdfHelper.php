<?php

require_once('vendor/fpdf186/fpdf.php');  // Incluir la biblioteca FPDF

class PdfHelper {

    private $pdf;

    public function __construct() {
        // Inicializar el objeto FPDF
        $this->pdf = new FPDF();
    }

    public function crearPDF($titulo, $contenido) {
        // Agregar una página
        $this->pdf->AddPage();

        // Establecer título
        $this->pdf->SetFont('Arial', 'B', 16);
        $this->pdf->Cell(200, 10, $titulo, 0, 1, 'C'); // Título centrado
        $this->pdf->Ln(10); // Salto de línea

        // Establecer contenido
        $this->pdf->SetFont('Arial', '', 12);
        $this->pdf->MultiCell(0, 10, $contenido);

        // Generar el archivo PDF
        $this->pdf->Output('I', 'documento.pdf'); // 'I' para visualizar en el navegador
    }

    // Otra función para generar PDF con más detalles
    public function crearPDFConTabla($titulo, $data) {
        $this->pdf->AddPage();
        $this->pdf->SetFont('Arial', 'B', 16);
        $this->pdf->Cell(200, 10, $titulo, 0, 1, 'C');
        $this->pdf->Ln(10);

        // Establecer fuente para la tabla
        $this->pdf->SetFont('Arial', 'B', 12);

        // Cabecera de la tabla
        $this->pdf->Cell(40, 10, 'ID', 1);
        $this->pdf->Cell(80, 10, 'Nombre', 1);
        $this->pdf->Cell(40, 10, 'Email', 1);
        $this->pdf->Cell(30, 10, 'Fecha de Creacion', 1);
        $this->pdf->Ln();

        // Establecer la fuente para los datos
        $this->pdf->SetFont('Arial', '', 12);

        // Agregar los datos de la tabla
        foreach ($data as $row) {
            $this->pdf->Cell(40, 10, $row['id'], 1);
            $this->pdf->Cell(80, 10, $row['nombre_completo'], 1);
            $this->pdf->Cell(40, 10, $row['email'], 1);
            $this->pdf->Cell(30, 10, $row['fecha_creacion'], 1);
            $this->pdf->Ln();
        }

        // Generar el archivo PDF
        $this->pdf->Output('I', 'documento_con_tabla.pdf');
    }
}

