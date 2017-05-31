<?php
    function ColoredTable($pdf, $w, $header, $data, $logo)
    {
        $width = 0;
        foreach ($w as $t) {
            $width += $t;
        }

        $pdf->SetFillColor(255, 255, 255);
        $pdf->SetTextColor(255);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->SetLineWidth(0.1);
        $pdf->SetFont('', 'B');

        $num_headers = count($header);

        $pdf->writeHTMLCell($width, 40, 40, 20, '<img src="http://www.deregenboog.org/sites/all/themes/uncinc_regenboog_theme/logo.png" alt="Home"/>');
        $pdf->Ln();
        $pdf->SetFillColor(255, 255, 255);
        $pdf->SetTextColor(0);
        $pdf->SetFont('');

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
            $fill=!$fill;
        }
        $pdf->Cell(array_sum($w), 0, '', 'T');
    }

App::import('Vendor', 'xtcpdf');
$pdf = new XTCPDF();

$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('De Regenboog Groep');
$pdf->SetTitle("Schorsing van $klant_naam");
$pdf->SetSubject('Schorsing');
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetAutoPageBreak(false);
$pdf->SetFont('helvetica', '', 12);

$pdf->AddPage();

if ($geslacht == 'M') {
    $geslacht = 'Dhr.';
} else {
    $geslacht = 'Mevr.';
}

$pdf->SetMargins(120, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);

$opt = array('separator' => ' ');
$datum_van_vandaag = $this->Date->show(date('Y-m-d'), $opt);
$begindatum_schorsing = $this->Date->show($begindatum_schorsing, $opt);
$einddatum_schorsing_pp = $this->Date->show($einddatum_schorsing_pp, $opt);

$datum_van_vandaag;
$klant_naam;
$begindatum_schorsing;
$opmerking_uit_schorsing;
$einddatum_schorsing_pp;
$locatie;

$logo = $html->url('/img/logo.png', true);
$data = array(
    array('SCHORSINGSFORMULIER'),
    array("Op {$datum_van_vandaag} bent u geschorst bij locatie(s) {$locatie}. De reden hiervoor is dat u zich niet aan de huisregels heeft gehouden. Hieronder vindt u een korte beschrijving van het incident en de duur van de schorsing. Ook informeren we u over de duur van schorsing en de manier waarop u bezwaar kunt maken."),
    array('Naam bezoeker', $klant_naam),
    array('Locatie(s)', $locatie),
    array('Naam locatiehoofd', $locatiehoofd),
    array('Reden schorsing', implode("\n", $redenen) . "\n\n" .$opmerking_uit_schorsing),
    array('Duur schorsing', $lengte_schorsing),
    array('Bijzonderheden', $bijzonderheden),
    array('Hoe kunt u bezwaar maken', 'Bent u het niet eens met uw schorsing, dan kunt u een afspraak maken voor een gesprek met het locatiehoofd of met de regiomanager.

U kunt zich wenden tot onafhankelijk cliënt-vertrouwenspersoon Lina Berger: 06 34293959.

U kunt ook terecht bij de onafhankelijke klachtencommissie van het POA: 06 14264972 info@klachtencommissiepoa.nl'),
    array('In en om al onze locaties staan rust, veiligheid en gezelligheid voor alle bezoekers, deelnemers en medewerkers voorop. We rekenen erop dat u hier in de toekomst weer een bijdrage aan zult leveren.'),
);

ColoredTable($pdf, [50, 100], ['', ''], $data, $logo);

$name_stripped = ereg_replace("[^A-Za-z0-9]", "-", $klant_naam);
echo $pdf->Output('schorsing-'.date('Y-m-d').'.pdf', 'I');
