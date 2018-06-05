<?php

namespace IzBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\ORM\EntityManager;
use AppBundle\Entity\Attachment;
use IzBundle\Entity\Document;
use Symfony\Component\Filesystem\Filesystem;

class DocumentCopyCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('iz:document:copy')
            ->addOption('dry-run')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var $entityManager EntityManager */
        $entityManager = $this->getContainer()->get('doctrine.orm.entity_manager');
        $archiveDir = realpath(__DIR__.'/../../../archive');
        $fs = new Filesystem();
        $count = 0;

        $documents = $entityManager->getRepository(Document::class)->findAll();
        foreach ($documents as $document) {
            $attachment = $entityManager->getRepository(Attachment::class)->findOneBY([
                'basename' => $document->getFilename(),
                'model' => 'IzDeelnemer',
            ]);

            if (!$attachment) {
                continue;
            }

            $source = sprintf('%s/media/%s/%s', $archiveDir, $attachment->dirname, $attachment->basename);
            $destination = sprintf('%s/iz/%s', $archiveDir, $document->getFilename());

            if ($fs->exists($source) && !$fs->exists($destination)) {
                $output->writeln(sprintf('Copy %s to %s', $source, $destination));
                if (!$input->getOption('dry-run')) {
                    $fs->copy($source, $destination);
                }
                ++$count;
            }
        }

        $output->writeln(sprintf('%d bestanden gekopieerd', $count));
    }
}
