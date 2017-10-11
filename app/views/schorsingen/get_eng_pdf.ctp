<?php
    function ColoredTable($pdf, $w, $data)
    {
        $width = 0;
        foreach ($w as $t) {
            $width += $t;
        }

        $fill = 0;
        $cnt = 0;
        foreach ($data as $row) {
            $cnt++;
            $cellcount = array();
            $startX = $pdf->GetX();
            $startY = $pdf->GetY();
            $uw = null;
            if (count($row) == 1) {
                $uw = $width;
            }
            foreach ($row as $key => $column) {
                $tuw=$w[$key];
                if (!empty($uw)) {
                    $tuw=$uw;
                }
                if ($cnt == 1) {
                    $cellcount[] = $pdf->MultiCell($tuw, 6, $column, 0, 'C', $fill, 0);
                } else {
                    $cellcount[] = $pdf->MultiCell($tuw, 6, $column, 0, 'L', $fill, 0);
                }
            }
            $pdf->SetXY($startX, $startY);
            $maxnocells = max($cellcount);
            $uw =null;
            if (count($row) == 1) {
                $uw =$width;
            }
            foreach ($row as $key => $column) {
                $tuw=$w[$key];
                if (!empty($uw)) {
                    $tuw=$uw;
                }
                $pdf->MultiCell($tuw, $maxnocells * 6, '', 'LTBR', 'L', $fill, 0);
            }
            $pdf->Ln();
            $fill = !$fill;
        }
        $pdf->Cell(array_sum($w), 0, '', 'T');
    }

App::import('Vendor', 'xtcpdf');
$pdf = new XTCPDF();
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('De Regenboog Groep');
$pdf->SetTitle("Suspension of $klant_naam");
$pdf->SetSubject('Suspension');
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
$pdf->SetMargins(20, 40, 20);
$pdf->SetAutoPageBreak(true);
$pdf->SetFont('helvetica', '', 10);
$pdf->SetFillColor(255, 255, 255);
$pdf->SetTextColor(0);

$pdf->AddPage();
$pdf->Image(('img/drg-logo-142px.jpg'), 160, 0, 40, 40);

$opt = array('separator' => ' ');
$datum_van_vandaag = $this->Date->show(date('Y-m-d'), $opt);
$begindatum_schorsing = $this->Date->show($begindatum_schorsing, $opt);
$einddatum_schorsing_pp = $this->Date->show($einddatum_schorsing_pp, $opt);

$data = array(
    array('SUSPENSION NOTE'),
    array("On {$begindatum_schorsing} you were suspended at our location(s) {$locatie}. The reason for your suspension was a disobedience of the house rules. Please find in the following table a short description of the incident and the length of your suspension. Also we would like to inform you about the way you can appeal against this decision."),
    array('Name visitor', $klant_naam),
    array('Location(s)', $locatie),
    array('Name head of location', $locatiehoofd),
    array('Reason for suspension', implode("\n", $redenen) . "\n\n" .trim($opmerking_uit_schorsing)),
    array('Length of suspension', sprintf('%s (%s t/m %s)', $lengte_schorsing, $begindatum_schorsing, $einddatum_schorsing_pp)),
    array('Special notes', $bijzonderheden),
    array('How to appeal', 'If you do not agree with your suspension, you can make an apointment with the head of location or the region manager.

You can contact the independent confidential mediator Lina Berger: 06 34293959.

You can also contact the independent Complaints Commission of POA: 06 14264972 info@klachtencommissiepoa.nl'),
    array('Safety, cosines, peace and quiet are our main concern for visitors, neighbours and workers alike inside and outside of our locations. We are confident of your cooperation and contribution to these values in the future.'),
);

ColoredTable($pdf, [40, 130], $data);
echo $pdf->Output('schorsing-'.date('Y-m-d').'.pdf', 'I');
