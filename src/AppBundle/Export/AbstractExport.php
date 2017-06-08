<?php

namespace AppBundle\Export;

abstract class AbstractExport implements ExportInterface
{
    /**
     * @var \PHPExcel
     */
    protected $excel;

    public function send($filename)
    {
        if (!$this->excel instanceof \PHPExcel) {
            throw new ExportException('Call create() before calling send()!');
        }

        if (headers_sent()) {
            throw new ExportException('Headers already sent!');
        }

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header(sprintf('Content-Disposition: attachment; filename="%s";', $filename));
        header('Content-Transfer-Encoding: UTF-8');
        header('Cache-Control: max-age=0');

        $this->getWriter()->save('php://output');
    }

    protected function getWriter()
    {
        return \PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');
    }
}
