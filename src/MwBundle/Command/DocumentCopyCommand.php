<?php

namespace MwBundle\Command;

use AppBundle\Entity\Attachment;
use Doctrine\ORM\EntityManager;
use MwBundle\Entity\Document;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem;

class DocumentCopyCommand extends \Symfony\Component\Console\Command\Command
{
    protected function configure()
    {
        $this
            ->setName('mw:document:copy')
            ->addArgument('filename', InputArgument::OPTIONAL, 'Filename to copy')
            ->addOption('dry-run')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        /* @var $entityManager EntityManager */
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
                'model' => 'Klant',
                'group' => 'mw',
            ]);

            if (!$attachment) {
                $output->writeln(sprintf('ERROR: Attachment %s not found!', $source, $destination));
                continue;
            }

            $source = sprintf('%s/media/%s/%s', $archiveDir, $attachment->dirname, $attachment->basename);
            $destination = sprintf('%s/mw/%s', $archiveDir, $document->getFilename());

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
        return 0;
    }
}
