<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Document;
use AppBundle\Entity\Overeenkomst;
use AppBundle\Entity\Toestemmingsformulier;
use AppBundle\Entity\Vog;
use AppBundle\Export\ExportException;
use AppBundle\Form\DocumentType;
use AppBundle\Form\DoelstellingFilterType;
use AppBundle\Form\DownloadVrijwilligersType;
use AppBundle\Service\DocumentDao;
use AppBundle\Service\DocumentDaoInterface;
use AppBundle\Service\DownloadsDao;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Vich\UploaderBundle\Handler\DownloadHandler;

/**
 * @Route("/download")
 */
class DownloadController extends AbstractController
{
    protected $downloadServices;

    public function __construct(iterable $downloadServices)
    {
        $this->downloadServices = $downloadServices;
    }

    /**
     * @Route("/")
     * @Template
     */
    public function indexAction(Request $request)
    {
        $form = $this->getForm(DownloadVrijwilligersType::class);
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
        $onderdelen = $form->get('onderdeel')->getData();

        //leluk maar iterator_apply geeft ook gedoe.
        $t = [];
        foreach($this->downloadServices as $v)
        {
            $t[$v->getServiceId()] = $v;
        }
        $this->downloadServices = $t;

        foreach($onderdelen as $serviceId)
        {
            if(!array_key_exists($serviceId,$this->downloadServices))
            {
                continue;
//                throw new ExportException(sprintf("Export with serviceId: %s cannot be found",$id));
            }
            $export = $this->downloadServices[$serviceId];
            $dao = $export->getDao();
            $collection = $dao->findAll(null, null);

            $sheet = $export->create($collection)->getSheet();
            unset($dao);
            unset($collection);

            $exports[] = $sheet;


        }
        array_pop($exports);//last one is already in... the last Export is used as carrier for the rest.
//        $export = $this->container->get($serviceId);
        $export = $this->downloadServices[$serviceId];
        foreach($exports as $sheet)
        {
            $export->addSheet($sheet);
        }

        return $export->getResponse(sprintf("Download Vrijwilligers %s.xlsx",(new \DateTime())->format('Y-m-d')));
    }

    /**
     * @Route("/view/{id}")
     */
    public function downloadsAction($id)
    {
        /**
         * PSEUDO:
         * naam en action / route werkte niet gaf 403. raar.
         *
         * ServiceId ophalen. Daarna doen zoals nu bij de download het geval is.
         * Evt later met filters bouwen.
         * Evt form maken met vinkjes per onderdeel, afhankelijk van hoe snel eea werkt.
         *
         * Hoe werkt het nu:
         * exports met de tag app.download worden in de service DownloadsDao gestopt, met hun Id
         * serviceId wordt alleen via de compiler pass toegevoegd, niet via de autowiring
         * Vraag me niet waarom. Misschien moet ik replaceArgument gebruiken  in de compilerPass Process functie, daar wordt ie als argument toegevoegd.
         * daar maar dat vind de rest nie tleuk.
         *
         *
         */

        if(!$this->container->has($id))
        {
         throw new ExportException(sprintf("Export with serviceId: %s cannot be found",$id));
        }
        $export = $this->container->get($id);
        $dao = $export->getDao();


        ini_set('memory_limit', '1024M');
        ini_set('max_execution_time', '300');

        $filename = $this->getDownloadFilename();
        $collection = $dao->findAll(null, null);

        return $export->create($collection)->getResponse($filename);
    }
}
