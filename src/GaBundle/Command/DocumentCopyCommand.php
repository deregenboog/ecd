<?php

namespace GaBundle\Command;

use AppBundle\Entity\Attachment;
use Doctrine\ORM\EntityManagerInterface;
use GaBundle\Entity\Document;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem;

class DocumentCopyCommand extends \Symfony\Component\Console\Command\Command
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('ga:document:copy')
            ->addArgument('filename', InputArgument::OPTIONAL, 'Filename to copy')
            ->addOption('dry-run')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $archiveDir = realpath(__DIR__.'/../../../archive');
        $fs = new Filesystem();
        $count = 0;

        $filename = $input->getArgument('filename');
        if ($filename) {
            $documents = $this->em->getRepository(Document::class)->findByFilename($filename);
        } else {
            $documents = $this->em->getRepository(Document::class)->findAll();
        }

        foreach ($documents as $document) {
            // prevent caching issue
            $this->em->clear();

            $attachment = $this->em->getRepository(Attachment::class)->findOneBy([
                'basename' => $document->getFilename(),
                'model' => 'Vrijwilliger',
                'group' => 'Groepsactiviteit',
            ]);

            if (!$attachment) {
                continue;
            }

            $source = sprintf('%s/media/%s/%s', $archiveDir, $attachment->dirname, $attachment->basename);
            $destination = sprintf('%s/ga/%s', $archiveDir, $document->getFilename());

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
