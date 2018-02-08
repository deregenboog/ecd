<?php

namespace HsBundle\Pdf;

\App::import('Vendor', 'xtcpdf');

class PdfFactuur extends \XTCPDF
{
    public function __construct($html)
    {
        parent::__construct();

        $this->SetCreator(PDF_CREATOR);
        $this->SetAuthor('Homeservice Amsterdam');
        $this->setPrintHeader(false);
        $this->xfootertext = 'Uw betaling kunt u overmaken op bankrekeningnummer NL46 INGB 0000215793 o.v.v. factuurnummer ten name van Stichting De Regenboog Groep.';
        $this->SetFont('helvetica', '', 10);

        $this->SetTitle('Factuur '.$entity);
        $this->SetSubject('Factuur Homeservice');

        $this->AddPage();
        $this->Image(('img/drg-logo-142px.jpg'), 5, 0, 40, 40);
        $this->writeHTMLCell(0, 0, null, 10, $html);
    }
}
