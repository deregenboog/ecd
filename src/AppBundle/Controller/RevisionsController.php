<?php

namespace AppBundle\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Gedmo\Loggable\Entity\LogEntry;
use Gedmo\Loggable\LogEntryInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/revisions")
 *
 * @Template
 */
class RevisionsController extends SymfonyController
{
    private EntityManagerInterface $em;

    public function __construct(
        EntityManagerInterface $em
    ) {
        $this->em = $em;
    }

    /**
     * @Route("/")
     *
     * @Template
     */
    public function indexAction(Request $request)
    {
        $objectClass = $request->get('class', '');
        $objectId = $request->get('id', '');

        $entries = $this->em->getRepository(LogEntry::class)->findBy(
            [
                'objectClass' => $objectClass,
                'objectId' => $objectId,
            ],
            ['loggedAt' => 'asc']
        );

        $revisions = [];
        foreach ($entries as $i => $entry) {
            $revision = [
                'logged_at' => $entry->getLoggedAt(),
                'username' => $entry->getUsername(),
                'action' => $entry->getAction(),
            ];
            switch ($entry->getAction()) {
                case LogEntryInterface::ACTION_CREATE:
                case LogEntryInterface::ACTION_UPDATE:
                    foreach ($entry->getData() as $k => $v) {
                        $revision['changes'][$k] = [
                            'from' => $i > 0 ? $revisions[$i - 1]['changes'][$k]['to'] : null,
                            'to' => $v,
                        ];
                    }
                    break;
                case LogEntryInterface::ACTION_REMOVE:
                    break;
            }
            $revisions[] = $revision;
        }

        foreach ($revisions as &$revision) {
            if (LogEntryInterface::ACTION_CREATE === $revision['action']) {
                unset($revision['changes']);
            }
        }

        return [
            'object_class' => $objectClass,
            'object_id' => $objectId,
            'revisions' => $revisions,
        ];
    }
}
