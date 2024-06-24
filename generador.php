<?php
require "vendor/autoload.php"; // Carga las dependencias de Composer


use PhpOffice\PhpSpreadsheet\IOFactory;
use setasign\Fpdi\Fpdi;
use PHPMailer\PHPMailer\Exception;


function generador(){

    $ruta = "documentos/Inscripciones EIJE 2024.xlsx";
    $documento = IOFactory::Load($ruta);
    $hoja = $documento->getActiveSheet();
    
    $dataArray = $hoja->toArray();


    foreach ($dataArray as $index => $row) {
    
        if ($index == 0) {
            continue;
        }
        
        $row[1] = mb_convert_encoding($row[1], 'ISO-8859-1');
    
        $pdf = new FPDI();
        $pageCount = $pdf->setSourceFile('documentos\Certificado asistencia EIJE 2024.pdf');
        $templateId = $pdf->importPage(1); // Página 1 del archivo original
    
        $pdf->AddPage();
        $pdf->useTemplate($templateId, 10, 10, 200); // Coloca la página importada en la posición deseada
    
        $pdf->SetFont('Arial', '', 12);

        if (!empty($row[2])){
            $pdf->SetXY(160, 132);
            $pdf->Cell(0, 10, "$row[2]", 0, 1, 'L');
        }else{
            $pdf->SetFillColor(255,255,255);
            $pdf->Rect(137,135,20,5,'F');
        }

        // Agrega texto adicional
        $pdf->SetXY(35, 132);
        $pdf->Cell(0, 10, $row[1], 0, 1, 'L');
        $pdf->SetXY(100, 201);
        $pdf->Cell(0, 10, "$row[3]", 0, 1, 'L');
    
        try{
            $pdf->Output('F', 'documentos/pdf/' . trim($row[5]));
        } catch (Exception $e) {
            echo 'Error al leer el archivo: ' . $e->getMessage();
        }
    }
    echo "Se genero correctamente";
}


?>