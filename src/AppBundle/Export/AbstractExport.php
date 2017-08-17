<?php

namespace AppBundle\Export;

use Symfony\Component\HttpFoundation\Response;

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

    public function getResponse($filename)
    {
        if (!$this->excel instanceof \PHPExcel) {
            throw new ExportException('Call create() before calling getResponse()!');
        }

        ob_start();
        $this->getWriter()->save('php://output');
        $content = ob_get_clean();

        return new Response($content, Response::HTTP_OK, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => sprintf(': attachment; filename="%s";', $filename),
            'Content-Transfer-Encoding' => 'UTF-8',
            'Cache-Control' => 'max-age=0',
        ]);
    }

    protected function getWriter()
    {
        return \PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');
    }
}
