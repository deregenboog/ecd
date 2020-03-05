<?php

namespace IzBundle\Command;

use AppBundle\Entity\Attachment;
use Doctrine\ORM\EntityManagerInterface;
use IzBundle\Entity\Document;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem;

class DocumentCopyCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('iz:document:copy')
            ->addArgument('filename', InputArgument::OPTIONAL, 'Filename to copy')
            ->addOption('dry-run')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /* @var $entityManager EntityManagerInterface */
        $entityManager = $this->getContainer()->get('doctrine.orm.entity_manager');
        $archiveDir = realpath(__DIR__.'/../../../archive');
        $fs = new Filesystem();
        $count = 0;

        $filename = $input->getArgument('filename');
        if ($filename) {
            $documents = $entityManager->getRepository(Document::class)->findByFilename($filename);
        } else {
            $documents = $entityManager->getRepository(Document::class)->findAll();
        }

        foreach ($documents as $document) {
            // prevent caching issue
            $entityManager->clear();

            $attachment = $entityManager->getRepository(Attachment::class)->findOneBy([
                'basename' => $document->getFilename(),
                'model' => 'IzDeelnemer',
            ]);

            if (!$attachment) {
                continue;
            }

            $source = sprintf('%s/media/%s/%s', $archiveDir, $attachment->dirname, $attachment->basename);
            $destination = sprintf('%s/iz/%s', $archiveDir, $document->getFilename());

            try {
                $output->writeln(sprintf('Copy %s to %s', $source, $destination));
                if (!$input->getOption('dry-run')) {
                    $fs->copy($source, $destination, true);
                }
                ++$count;
            } catch (FileNotFoundException $e) {
                $output->writeln('Source does not exists');
            } catch (IOException $e) {
                $output->writeln('Error copying');
            }
        }

        $output->writeln(sprintf('%d bestanden gekopieerd', $count));
    }
}
