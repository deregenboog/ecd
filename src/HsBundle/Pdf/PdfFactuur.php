<?php

namespace HsBundle\Pdf;

use HsBundle\Entity\Factuur;

class PdfFactuur extends \TCPDF
{
    private $footerText = 'Uw betaling graag overmaken o.v.v. factuurnummer op bankrekeningnummer NL44 RABO 0331 1944 14 ten name van Stichting De Regenboog Groep.';

    public function __construct($html, Factuur $entity)
    {
        parent::__construct();

        $this->SetCreator(PDF_CREATOR);
        $this->SetAuthor('Homeservice Amsterdam');
        $this->setPrintHeader(false);
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
