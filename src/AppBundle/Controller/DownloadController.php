<?php

namespace AppBundle\Controller;

use AppBundle\Export\GenericExport;
use AppBundle\Service\AbstractDao;
use AppBundle\Form\DownloadVrijwilligersType;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/download")
 */
class DownloadController extends AbstractController
{
    private $downloadServices = [];

    public function __construct(iterable $downloadServices)
    {
        $this->downloadServices = $downloadServices instanceof \Traversable ? iterator_to_array($downloadServices) : $downloadServices;
    }

    /**
     * @Route("/")
     * @Template
     */
    public function indexAction(Request $request)
    {
        $form = $this->getForm(DownloadVrijwilligersType::class, null, [
            'download_services' => $this->downloadServices,
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            return $this->handleDownloads($form);
        }

        return [
            'form' => $form->createView(),
        ];
    }

    private function handleDownloads($form)
    {
        ini_set('memory_limit', '768M');
        ini_set('max_execution_time', '300');

        $exports = [];
        foreach ($form->get('onderdeel')->getData() as $i) {
            /** @var GenericExport $exportService */
            $exportService = $this->downloadServices[$i];

            /** @var AbstractDao $dao */
            $dao = $exportService->getDao();
            $collection = $dao->findAll(null, null);
            unset($dao);

            // get worksheet
            $exports[] = $exportService->create($collection)->getSheet();
            unset($collection);
        }

        // create new spreadsheet and remove default worksheet
        $spreadsheet = new Spreadsheet();
        $spreadsheet->removeSheetByIndex(0);
        foreach ($exports as $i => $sheet) {
            // add sheets from exports as external sheets
            // @see https://phpspreadsheet.readthedocs.io/en/latest/topics/worksheets/#copying-worksheets
            $spreadsheet->addExternalSheet(clone $sheet, $i);
        }

        // create writer
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = sprintf("Download Vrijwilligers %s.xlsx", (new \DateTime())->format('Y-m-d'));

        // write to output buffer
        // @see https://www.php.net/manual/en/wrappers.php.php
        ob_start();
        $writer->save('php://output');
        $content = ob_get_clean();

        return new Response($content, Response::HTTP_OK, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => sprintf('attachment; filename="%s";', $filename),
            'Content-Transfer-Encoding' => 'UTF-8',
            'Cache-Control' => 'max-age=0',
        ]);
    }
}
