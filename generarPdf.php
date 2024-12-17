<?php
ob_start(); // Inicia el buffer de salida
require('fpdf/fpdf.php'); // Incluye la biblioteca FPDF
require_once 'config.php'; // Incluye la conexión a la base de datos

class PDF extends FPDF
{
    // Encabezado de la página
    function Header()
    {
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(0, 10, 'Listado de Productos', 0, 1, 'C');
        $this->Ln(10);
    }

    // Pie de página
    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Pagina ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }
}

// Crea un nuevo objeto PDF
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial', '', 10);

// Consulta para obtener los datos
try {
    $sql = "SELECT * FROM productos";
    $resultado = $conexion->prepare($sql);
    $resultado->execute();

    // Crear encabezados de la tabla en el PDF
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(30, 10, 'nombre', 1, 0, 'C');
    $pdf->Cell(50, 10, 'categoria', 1, 0, 'C');
    $pdf->Cell(30, 10, 'pvp', 1, 0, 'C');
    $pdf->Cell(40, 10, 'stock', 1, 0, 'C');
    $pdf->Cell(20, 10, 'observaciones', 1, 0, 'C');
    
    // Agregar filas de datos al PDF
    $pdf->SetFont('Arial', '', 10);
    while ($fila = $resultado->fetch(PDO::FETCH_ASSOC)) {
        $pdf->Cell(30, 10, $fila['nombre'], 1);
        $pdf->Cell(50, 10, iconv('UTF-8', 'ISO-8859-1', $fila['categoria']), 1);
        $pdf->Cell(20, 10, $fila['pvp'] . chr(128), 1); //Usamos el codigo del € en html
        $pdf->Cell(20, 10, $fila['stock'] . chr(128), 1); //Usamos el
        $pdf->Cell(30, 10, $fila['observaciones'], 1);
    }
} catch (PDOException $e) {
    $pdf->Cell(0, 10, 'Error al obtener datos: ' . $e->getMessage(), 0, 1);
}

// Salida del PDF
ob_end_clean(); // Limpia el buffer de salida
$pdf->Output('I', 'Listado_Productos.pdf'); // Muestra el PDF en el navegador

?>
