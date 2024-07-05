<?php

namespace InloopBundle\Pdf;

class PdfBrief extends \TCPDF
{
    public function __construct($html)
    {
        parent::__construct(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $this->SetCreator(PDF_CREATOR);
        $this->SetAuthor('De Regenboog Groep');
        $this->setPrintHeader(false);
        $this->setPrintFooter(false);
        $this->SetFont('dejavusans', '', 10);

        $this->SetTitle('Doorverwijsbrief AMOC');
        $this->SetSubject('Doorverwijsbrief AMOC');

        $this->AddPage();
        $this->Image('img/drg-logo-142px.jpg', null, null, 40, 40, null, null, null, false, 300, 'R');
        $this->writeHTMLCell(null, null, 15, 20, $this->spaceless($html));
        $this->AddPage();
        $this->Image('img/map_amoc_west.png', null, null, 1137, 1472, 'PNG', null, null, false, 300, 'C', false, false, 0, false, false, true);
    }

    private function spaceless($html)
    {
        return trim(preg_replace('/>\s+</', '><', $html));
    }
}
