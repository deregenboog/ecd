<?php

namespace HsBundle\Pdf;

use HsBundle\Entity\Herinnering;

class PdfHerinnering extends \TCPDF
{
    private $footerText = 'Uw betaling kunt u overmaken op bankrekeningnummer NL46 INGB 0000215793 o.v.v. factuurnummer ten name van Stichting De Regenboog Groep.';

    public function __construct($html, Herinnering $entity)
    {
        parent::__construct();

        $this->SetCreator(PDF_CREATOR);
        $this->SetAuthor('Homeservice Amsterdam');
        $this->setPrintHeader(false);
        $this->SetFont('helvetica', '', 10);

        $this->SetTitle('Betalingsherinnering '.$entity->getId());
        $this->SetSubject('Betalingsherinnering Homeservice');

        $this->AddPage();
        $this->Image(('img/drg-logo-142px.jpg'), 10, 0, 40, 40);
        $this->writeHTMLCell(null, null, 15, 10, $html);
    }

    /**
     * {@inheritdoc}
     */
    public function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('helvetica', 'I', 8);
        $this->Cell(0, 10, $this->footerText, 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }
}
