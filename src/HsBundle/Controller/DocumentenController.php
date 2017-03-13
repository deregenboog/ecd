<?php

namespace HsBundle\Controller;

use HsBundle\Entity\Document;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Controller\SymfonyController;

/**
 * @Route("/hs/documenten")
 */
class DocumentenController extends SymfonyController
{
    /**
     * @Route("/{filename}")
     */
    public function view($filename)
    {
        $document = $this->getEntityManager()->getRepository(Document::class)
            ->findOneByFilename($filename);

        $downloadHandler = $this->get('vich_uploader.download_handler');

        return $downloadHandler->downloadObject($document, 'file');
    }
}
