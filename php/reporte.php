<?php
require_once('../fpdf/fpdf.php');
include("conexion.php");
session_start();

if (!isset($_SESSION['id'])) {
    die("No autorizado.");
}

header('Content-Type: application/pdf');
header('Content-Disposition: inline; filename="reporte_productos.pdf"');

$id_usuario = $_SESSION['id'];
$nombre_usuario = $_SESSION['nombre'] . ' ' . $_SESSION['apellido'];
$fecha_actual = date("Y-m-d");
$hora_actual = date("H:i:s");

class PDF extends FPDF {
    public $nombre_usuario;
    public $fecha_actual;
    public $hora_actual;

    function Header() {
        if (file_exists('../img/logo.png')) {
            $this->Image('../img/logo.png',10,6,20);
        }

        $this->SetFont('Arial','B',14);
        $this->Cell(0,10,'REPORTE DE PRODUCTOS - Tienda SyC',0,1,'C');
        $this->Ln(3);

        $this->SetFont('Arial','',10);
        $this->Cell(0,6,"Nombre: {$this->nombre_usuario}",0,1,'L');
        $this->Cell(0,6,"Cargo: Inventariador",0,1,'L');
        $this->Cell(0,6,"Fecha: {$this->fecha_actual} {$this->hora_actual}",0,1,'L');
        $this->Ln(5);

        // Estilo encabezado tabla
        $this->SetFillColor(108, 141, 250); // Azul: #6c8dfa
        $this->SetTextColor(255, 255, 255); // Blanco
        $this->SetDrawColor(0, 0, 0);
        $this->SetFont('Arial','B',10);

        $this->Cell(30,10,'Nombre',1,0,'C',true);
        $this->Cell(30,10,'Marca',1,0,'C',true);
        $this->Cell(30,10,'Categoria',1,0,'C',true);
        $this->Cell(20,10,'Stock',1,0,'C',true);
        $this->Cell(25,10,'Estado',1,0,'C',true);
        $this->Cell(30,10,'Fecha',1,0,'C',true);
        $this->Cell(25,10,'Hora',1,1,'C',true);
    }

    function Footer() {
        $this->SetY(-20);
        $this->SetFont('Arial','I',8);
        $this->Cell(0,5,"Generado por: {$this->nombre_usuario}",0,1,'L');
        $this->Cell(0,5,"Fecha: {$this->fecha_actual} - Hora: {$this->hora_actual}",0,1,'L');
        $this->Cell(0,5,"Página " . $this->PageNo() . " de {nb}",0,1,'C');
        $this->Ln(2);
        $this->Cell(0,5,"PLANTILLA DE INVENTARIO - http://Tienda_SyC",0,0,'C');
    }
}

$pdf = new PDF();
$pdf->nombre_usuario = $nombre_usuario;
$pdf->fecha_actual = $fecha_actual;
$pdf->hora_actual = $hora_actual;
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','',10);
$pdf->SetDrawColor(0, 0, 0); // Borde negro

// Consulta
$sql = "SELECT nombre, marca, categoria, cantidad, estado, fecha_ingreso FROM productos WHERE id_usuario = $id_usuario";
$result = $conexion->query($sql);

// Alternar filas
$alternar = true;
while($row = $result->fetch_assoc()) {
    $fechaHora = explode(" ", $row['fecha_ingreso']);
    $fecha = $fechaHora[0];
    $hora = $fechaHora[1];

    // Color intercalado
    $pdf->SetFillColor($alternar ? 204 : 255, $alternar ? 219 : 255, $alternar ? 253 : 255); // #ccdbfd y blanco
    $alternar = !$alternar;

    $pdf->SetTextColor(0, 0, 0); // Texto negro
    $pdf->SetFont('Arial','',10);

    // Celdas
    $pdf->Cell(30,10,$row['nombre'],1,0,'C',true);
    $pdf->Cell(30,10,$row['marca'],1,0,'C',true);
    $pdf->Cell(30,10,$row['categoria'],1,0,'C',true);
    $pdf->Cell(20,10,$row['cantidad'],1,0,'C',true);
    $pdf->Cell(25,10,$row['estado'],1,0,'C',true);
    $pdf->Cell(30,10,$fecha,1,0,'C',true);
    $pdf->Cell(25,10,$hora,1,1,'C',true);
}

ob_clean();
$pdf->Output();
exit;
?>
