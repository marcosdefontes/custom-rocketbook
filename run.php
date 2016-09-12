<?php
require_once('tcpdf_include.php');

$debug = 5;

class MYPDF extends TCPDF {
    //Page header
    public function Header() {
        // get the current page break margin
        $bMargin = $this->getBreakMargin();
        // get current auto-page-break mode
        $auto_page_break = $this->AutoPageBreak;
        // disable auto-page-break
        $this->SetAutoPageBreak(false, 0);
        // set bacground image
        $img_file = K_PATH_IMAGES.'A4_OP10.jpg';
        $this->Image($img_file, 0, 0, 210, 297, '', '', '', false, 300, '', false, false, 0);



        // restore auto-page-break status
        $this->SetAutoPageBreak($auto_page_break, $bMargin);
        // set the starting point for the page content
        $this->setPageMark();
    }

    public function Footer() {
        // Position at 15 mm from bottom
        $this->SetY(-1);

    }
}

// create new PDF document
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);



// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('RocketBook');
$pdf->SetTitle('RocketBook Pages');
$pdf->SetSubject('');
$pdf->SetKeywords('');

// set default header data
//$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 001', PDF_HEADER_STRING, array(0,64,255), array(0,64,128));
//$pdf->setFooterData(array(0,64,0), array(0,64,128));

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
    require_once(dirname(__FILE__).'/lang/eng.php');
    $pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

// set default font subsetting mode
$pdf->setFontSubsetting(true);

// set style for barcode
$style = array(
    'border' => false,
    'padding' => 0,
    'vpadding' => 'auto',
    'hpadding' => 'auto',
    'fgcolor' => array(0,0,0),
    'bgcolor' => false,
    'module_width' => 1, // width of a single module in points
    'module_height' => 1 // height of a single module in points
);

// Set font
// dejavusans is a UTF-8 Unicode font, if you only need to
// print standard ASCII chars, you can use core fonts like
// helvetica or times to reduce file size.
$pdf->SetFont('dejavusans', '', 8, '', true);



for( $pagina = 1 ; $pagina <= 220 ; $pagina++ ){

    $pdf->AddPage();

    // get the current page break margin
    $bMargin = $pdf->getBreakMargin();
    // get current auto-page-break mode
    $auto_page_break = $pdf->getAutoPageBreak();
    // disable auto-page-break
    $pdf->SetAutoPageBreak(false, 0);
    // set bacground image
    $img_file = K_PATH_IMAGES.'A4_OP10-a.jpg';
    $pdf->Image($img_file, 0, 0, 210, 297, '', '', '', false, 300, '', false, false, 0);
    // restore auto-page-break status
    $pdf->SetAutoPageBreak($auto_page_break, $bMargin);
    // set the starting point for the page content
    $pdf->setPageMark();


    $pageFormat = sprintf('%02d', $pagina);
    $html = "<p style=\"color:#000000;\">P&aacute;gina $pageFormat</p>";
    $pdf->writeHTMLCell(0, 0, 167, 21, $html, 0, 1, 0, true, 'L', true);

    $textQRCode = "P" . $pageFormat ." V05 S0000000";

    $pdf->write2DBarcode($textQRCode, 'QRCODE,H', 163, 220, 19, 19, $style, 'N');
}

// ---------------------------------------------------------

// Close and output PDF document
// This method has several options, check the source code documentation for more information.
$pdf->Output('/home/00448406160/Desktop/example_001.pdf', 'F');
