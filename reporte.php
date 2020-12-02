<?php
require('fpdf/fpdf.php');

class PDF extends FPDF
{
    function Header()
    {
        $this->Image('assets/images/Logo_EdoMex.png', 10, 5, 22 );
        $this->Image('assets/images/Logo_Carta.gif', 145, 9, 60 );
        $this->SetFont('Arial','B',12);
        $this->Ln(12);
        $this->Cell(195,10,utf8_decode('CEDULA DE FACULTADES Y REGISTRO DE FIRMA'),1,1,'C'); 
        
        $this->SetFont('Arial','B',10);
        $this->Ln(2);
        $this->Cell(195,10.5,'',1,1,'C');
        $this->SetXY(12,35);
        $this->Cell(50,5,'UNIDAD ORGANICA:',0,1,'L');
        $this->SetXY(31.5,39.5);
        $this->Cell(50,5,'PUESTO:',0,1,'L');

        $this->SetFont('Arial','B',10);
        $this->SetXY(10,46.5);   
        $this->Cell(97.5,5,'FACULTAD',1,1,'C');
        $this->SetXY(107.4,46.5);   
        $this->Cell(97.5,5,'REFERENCIA NORMATIVA',1,1,'C');
    }
        
    function Footer()
    {
        $this->SetFont('Arial','B',10);                        
        $this->SetXY(10,-63);               
        $this->Cell(26,5,'VIGENCIA',1,1,'C');
        $this->SetXY(37,-63);
        $this->Cell(70,5,'NOMBRE DEL TITULAR',1,1,'C');
        $this->SetXY(108,-63);
        $this->Cell(48,5,'FIRMA',1,1,'C');
        $this->SetXY(157,-63);
        $this->Cell(48,5,'ANTEFIRMA',1,1,'C');
        $this->SetXY(108,-57);   
        $this->Cell(48,20,'',1,1,'C');
        $this->SetXY(157,-57);   
        $this->Cell(48,20,'',1,1,'C');
        $this->SetFont('Arial','B',10);
        $this->SetXY(10,-36);   
        $this->Cell(146,5,'MANUAL DE FACTULTADES Y REGISTRO DE FIRMA',1,1,'C');
        $this->SetXY(157,-36);
        $this->SetFont('Arial','I', 8);
        $this->Cell(48,5, utf8_decode('Página ').$this->PageNo().'/{nb}',1,1,'C');
        $this->SetXY(10,-30);
        $this->SetFont('Arial','I', 6);
        $this->MultiCell(195,5, utf8_decode('NOTA: El original de este documento obra en poder de la UNIDAD DE MODERNIZACION ADMINISTRATIVA, por lo que queda prohibido su reproducción, alteración o uso inadecuado, lo que será sancionado de acuerdo a la norvatividad vigente.'),0,'L'); 
    }     
}

include_once("Connections/cedulas.php");

$cve_emp = $_GET['recordID'];

try {
	$sql = "SELECT e.nombre, g.grado, p.puesto, d.departamento, c.vigencia, p.puesto AS nomPuesto, c.puesto, c.archivo 
    FROM cedulas c JOIN empleados e 
    ON c.empleado = e.clave
    JOIN grados g
    ON c.grado = g.id
    JOIN puestos p
    ON c.puesto = p.clave
    JOIN departamentos d
    ON c.area = d.clave	
    WHERE c.empleado = ?";
    $ced = $conexion->prepare($sql);
    $ced->execute(array($cve_emp));
    $cedulas = $ced->fetch(PDO::FETCH_ASSOC);    
}catch(PDOException $e) {
	echo $e->getMessage();
}

$pdf = new PDF('P','mm',array(216,356));
$pdf->AliasNbPages();

$Y = 51.6;

for($i = 1; $i <= 52; $i++ ) {
    $sqlPf = "SELECT f.clave, f.facultad, f.referencia, p.fac_$i FROM puestos p JOIN facultades f ON p.fac_$i = f.clave WHERE p.clave = ?";
	$pf = $conexion->prepare($sqlPf);
	$pf->execute(array($cedulas['puesto']));
    $pfac = $pf->fetch(PDO::FETCH_ASSOC);    
    
    if($pfac['fac_'.$i] != NULL) {        
        if ($i == 1) {
            $pdf->AddPage();
            $Y = 51.6;
            $pdf->SetFont('Arial','',8);
            $pdf->SetXY(48,35);   
            $pdf->Cell(50,5,utf8_decode($cedulas['departamento']),0,1,'L');
            $pdf->SetXY(48,39.5);   
            $pdf->Cell(50,5,utf8_decode($cedulas['nomPuesto']),0,1,'L');
            $pdf->SetFont('Arial','',8);
            $pdf->SetXY(10,-57);   
            $pdf->Cell(26,20,utf8_decode($cedulas['vigencia']),1,1,'C');
            $pdf->SetXY(37,-57);   
            $pdf->Cell(70,20,utf8_decode($cedulas['nombre']),1,1,'C');            
        }

        if ($i >= 1 && $i <= 12) {            
            $pdf->SetFont('Arial','',8);
            $pdf->SetXY(10,$Y);
            $pdf->Cell(97.5,20,'',1,1,'C'); 
            $pdf->SetXY(10,$Y);  
            $pdf->MultiCell(97.5,5,utf8_decode($pfac['facultad']),0,'L');
            $pdf->SetXY(107.4,$Y);
            $pdf->Cell(97.5,20,'',1,1,'C');      
            $pdf->SetXY(107.4,$Y);
            $pdf->MultiCell(97.5,5,utf8_decode($pfac['referencia']),0,'L');
            $Y = $Y + 20;
        }

        if ($i == 13) {
            $pdf->AddPage();
            $Y = 51.6;
            $pdf->SetFont('Arial','',8);
            $pdf->SetXY(48,35);   
            $pdf->Cell(50,5,utf8_decode($cedulas['departamento']),0,1,'L');
            $pdf->SetXY(48,39.5);   
            $pdf->Cell(50,5,utf8_decode($cedulas['nomPuesto']),0,1,'L');
            $pdf->SetFont('Arial','',8);
            $pdf->SetXY(10,-57);   
            $pdf->Cell(26,20,utf8_decode($cedulas['vigencia']),1,1,'C');
            $pdf->SetXY(37,-57);   
            $pdf->Cell(70,20,utf8_decode($cedulas['nombre']),1,1,'C');            
        }

        if ($i >= 13 && $i <= 24) {            
            $pdf->SetFont('Arial','',8);
            $pdf->SetXY(10,$Y);
            $pdf->Cell(97.5,20,'',1,1,'C'); 
            $pdf->SetXY(10,$Y);  
            $pdf->MultiCell(97.5,5,utf8_decode($pfac['facultad']),0,'L');
            $pdf->SetXY(107.4,$Y);
            $pdf->Cell(97.5,20,'',1,1,'C');      
            $pdf->SetXY(107.4,$Y);
            $pdf->MultiCell(97.5,5,utf8_decode($pfac['referencia']),0,'L');
            $Y = $Y + 20;            
        }

        if ($i == 25) {
            $pdf->AddPage();
            $Y = 51.6;
            $pdf->SetFont('Arial','',8);
            $pdf->SetXY(48,35);   
            $pdf->Cell(50,5,utf8_decode($cedulas['departamento']),0,1,'L');
            $pdf->SetXY(48,39.5);   
            $pdf->Cell(50,5,utf8_decode($cedulas['nomPuesto']),0,1,'L');
            $pdf->SetFont('Arial','',8);
            $pdf->SetXY(10,-57);   
            $pdf->Cell(26,20,utf8_decode($cedulas['vigencia']),1,1,'C');
            $pdf->SetXY(37,-57);   
            $pdf->Cell(70,20,utf8_decode($cedulas['nombre']),1,1,'C');            
        }
    
        if ($i >= 25 && $i <= 36) {            
            $pdf->SetFont('Arial','',8);
            $pdf->SetXY(10,$Y);
            $pdf->Cell(97.5,20,'',1,1,'C'); 
            $pdf->SetXY(10,$Y);  
            $pdf->MultiCell(97.5,5,utf8_decode($pfac['facultad']),0,'L');
            $pdf->SetXY(107.4,$Y);
            $pdf->Cell(97.5,20,'',1,1,'C');      
            $pdf->SetXY(107.4,$Y);
            $pdf->MultiCell(97.5,5,utf8_decode($pfac['referencia']),0,'L');
            $Y = $Y + 20;            
        }

        if ($i == 37) {
            $pdf->AddPage();
            $Y = 51.6;
            $pdf->SetFont('Arial','',8);
            $pdf->SetXY(48,35);   
            $pdf->Cell(50,5,utf8_decode($cedulas['departamento']),0,1,'L');
            $pdf->SetXY(48,39.5);   
            $pdf->Cell(50,5,utf8_decode($cedulas['nomPuesto']),0,1,'L');
            $pdf->SetFont('Arial','',8);
            $pdf->SetXY(10,-57);   
            $pdf->Cell(26,20,utf8_decode($cedulas['vigencia']),1,1,'C');
            $pdf->SetXY(37,-57);   
            $pdf->Cell(70,20,utf8_decode($cedulas['nombre']),1,1,'C');            
        }
    
        if ($i >= 37 && $i <= 48) {            
            $pdf->SetFont('Arial','',8);
            $pdf->SetXY(10,$Y);
            $pdf->Cell(97.5,20,'',1,1,'C'); 
            $pdf->SetXY(10,$Y);  
            $pdf->MultiCell(97.5,5,utf8_decode($pfac['facultad']),0,'L');
            $pdf->SetXY(107.4,$Y);
            $pdf->Cell(97.5,20,'',1,1,'C');      
            $pdf->SetXY(107.4,$Y);
            $pdf->MultiCell(97.5,5,utf8_decode($pfac['referencia']),0,'L');
            $Y = $Y + 20;            
        }

        if ($i == 49) {
            $pdf->AddPage();
            $Y = 51.6;
            $pdf->SetFont('Arial','',8);
            $pdf->SetXY(48,35);   
            $pdf->Cell(50,5,utf8_decode($cedulas['departamento']),0,1,'L');
            $pdf->SetXY(48,39.5);   
            $pdf->Cell(50,5,utf8_decode($cedulas['nomPuesto']),0,1,'L');
            $pdf->SetFont('Arial','',8);
            $pdf->SetXY(10,-57);   
            $pdf->Cell(26,20,utf8_decode($cedulas['vigencia']),1,1,'C');
            $pdf->SetXY(37,-57);   
            $pdf->Cell(70,20,utf8_decode($cedulas['nombre']),1,1,'C');            
        }

        if ($i >= 49 && $i <= 52) {            
            $pdf->SetFont('Arial','',8);
            $pdf->SetXY(10,$Y);
            $pdf->Cell(97.5,20,'',1,1,'C'); 
            $pdf->SetXY(10,$Y);  
            $pdf->MultiCell(97.5,5,utf8_decode($pfac['facultad']),0,'L');
            $pdf->SetXY(107.4,$Y);
            $pdf->Cell(97.5,20,'',1,1,'C');      
            $pdf->SetXY(107.4,$Y);
            $pdf->MultiCell(97.5,5,utf8_decode($pfac['referencia']),0,'L');
            $Y = $Y + 20;            
        }
    }    
}

$pdf->Output();
?>