<?php

App::import('Vendor', 'tcpdf/tcpdf');

class XTCPDF extends TCPDF
{
    public $xheadertext = 'PDF created using CakePHP and TCPDF';
    public $xheadercolor = [0, 0, 200];
    public $xfootertext = 'Copyright Â© %d XXXXXXXXXXX. All rights reserved.';
    public $xfooterfont = PDF_FONT_NAME_MAIN;
    public $xfooterfontsize = 8;

    /**
     * Overwrites the default header
     * set the text in the view using
     *    $fpdf->xheadertext = 'YOUR ORGANIZATION';
     * set the fill color in the view using
     *    $fpdf->xheadercolor = array(0,0,100); (r, g, b)
     * set the font in the view using
     *    $fpdf->setHeaderFont(array('YourFont','',fontsize));.
     */
    public function Header()
    {
        list($r, $b, $g) = $this->xheadercolor;
        $this->setY(10); // shouldn't be needed due to page margin, but helas, otherwise it's at the page top
        $this->SetFillColor($r, $b, $g);
        $this->SetTextColor(0, 0, 0);
        $this->Cell(0, 20, '', 0, 1, 'C', 1);
        $this->Text(15, 26, $this->xheadertext);
    }

    /**
     * Overwrites the default footer
     * set the text in the view using
     * $fpdf->xfootertext = 'Copyright Â© %d YOUR ORGANIZATION. All rights reserved.';.
     */
    public function Footer()
    {
        $year = date('Y');
        $footertext = sprintf($this->xfootertext, $year);
        $this->SetY(-20);
        $this->SetTextColor(0, 0, 0);
        $this->SetFont($this->xfooterfont, '', $this->xfooterfontsize);
        $this->Cell(0, 8, $footertext, 'T', 1, 'C');
    }
}
