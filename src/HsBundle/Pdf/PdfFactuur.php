<?php

namespace HsBundle\Pdf;

use HsBundle\Entity\Factuur;

class PdfFactuur extends \TCPDF
{
    public function __construct($html, Factuur $entity)
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
        $this->Image(('img/drg-logo-142px.jpg'), 10, 0, 40, 40);
        $this->writeHTMLCell(null, null, 15, 10, $html);

        if (!$entity->isLocked()) {
            $this->SetFont('helvetica', 'B', 36);
            $this->SetTextColor(128, 128, 128);
            $this->writeHTMLCell(null, null, 130, 48, 'CONCEPT');
        }
    }
}
