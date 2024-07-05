<?php

namespace ScipBundle\Command;

use AppBundle\Entity\Geslacht;
use AppBundle\Entity\Klant;
use AppBundle\Entity\Land;
use AppBundle\Entity\Medewerker;
use AppBundle\Entity\Nationaliteit;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use ScipBundle\Entity\Beschikbaarheid;
use ScipBundle\Entity\Deelname;
use ScipBundle\Entity\Deelnemer;
use ScipBundle\Entity\Document;
use ScipBundle\Entity\Project;
use ScipBundle\Service\DeelnemerDaoInterface;
use ScipBundle\Service\DocumentDaoInterface;
use ScipBundle\Service\ProjectDaoInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Handler\UploadHandler;

class ImportCommand extends \Symfony\Component\Console\Command\Command
{
    /**
     * @var ProjectDaoInterface
     */
    private $projectDao;

    /**
     * @var DeelnemerDaoInterface
     */
    private $deelnemerDao;

    /**
     * @var DocumentDaoInterface
     */
    private $documentDao;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var Land
     */
    private $land;

    /**
     * @var Nationaliteit
     */
    private $nationaliteit;

    /**
     * @var Geslacht
     */
    private $geslacht;

    /**
     * @var OutputInterface
     */
    private $output;

    /**
     * @var UploadHandler
     */
    private $uploader;

    /**
     * @var array
     */
    private $deelnemers = [];

    public function __construct(
        EntityManagerInterface $em,
        UploadHandler $uploader,
        \ScipBundle\Service\ProjectDao $projectDao,
        \ScipBundle\Service\DeelnemerDao $deelnemerDao,
        \ScipBundle\Service\DocumentDao $documentDao
    ) {
        $this->entityManager = $em;
        $this->uploader = $uploader;
        $this->projectDao = $projectDao;
        $this->deelnemerDao = $deelnemerDao;
        $this->documentDao = $documentDao;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('scip:import')
            ->addArgument('file', InputArgument::REQUIRED)
            ->addArgument('path', InputArgument::REQUIRED)
        ;
    }

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->land = $this->entityManager->find(Land::class, 0);
        $this->nationaliteit = $this->entityManager->find(Nationaliteit::class, 0);
        $this->geslacht = $this->entityManager->find(Geslacht::class, 3);

        $this->output = $output;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // set higher auto-increment value to make new entities distinguishable
        $this->entityManager->getConnection()->exec('ALTER TABLE `klanten` AUTO_INCREMENT = 5650000');

        $file = $input->getArgument('file');
        $path = $input->getArgument('path');

        $data = $this->readData($file);

        $this->output->writeln('');
        $this->output->writeln('Importeren van projecten...');
        $projecten = $this->processProjecten($data);

        $this->output->writeln('');
        $this->output->writeln('Importeren van vrijwilligers met RIS-nummer...');
        $this->processDeelnemers(
            array_filter($data, function ($values) { return (bool) $values[7]; }),
            $projecten
        );

        $this->output->writeln('');
        $this->output->writeln('Importeren van vrijwilligers zonder RIS-nummer...');
        $this->processDeelnemers(
            array_filter($data, function ($values) { return !$values[7]; }),
            $projecten
        );

        $this->output->writeln('');
        $this->output->writeln('Importeren documenten...');
        $this->processDocumenten($data, $path);

        return 0;
    }

    private function processDocumenten($data, $path)
    {
        $medewerker = $this->entityManager->getRepository(Medewerker::class)->findOneBy([
            'username' => 'bhuttinga',
        ]);

        foreach (new \DirectoryIterator($path) as $item) {
            if ($item->isDir() && !$item->isDot()) {
                $this->output->writeln(sprintf('  - Directory: %s', $item->getFilename()));

                $values = preg_split('/\s/', $item->getFilename());
                $project = $this->projectDao->findOneByKpl($values[0]);
                if (!$project) {
                    $this->output->writeln(sprintf('    - Project niet gevonden: %s', $item->getPathname()));
                    continue;
                }

                try {
                    $iterator = new \DirectoryIterator($item->getPathname().'/Vrijwilligersdossier');
                } catch (\UnexpectedValueException $e) {
                    $iterator = new \DirectoryIterator($item->getPathname().'/vrijwilligersdossier');
                }
                foreach ($iterator as $item) {
                    if ($item->isDir() && !$item->isDot()) {
                        $this->output->writeln(sprintf('    - Directory: %s', $item->getFilename()));

                        $deelnemer = $this->findDeelnemerByDirname($item->getFilename(), $data);
                        if (!$deelnemer) {
                            continue;
                        }
                        $this->output->writeln(sprintf('      - Vrijwilliger gevonden (%s)', $deelnemer));

                        foreach (new \DirectoryIterator($item->getPathname()) as $item) {
                            if ($item->isFile()) {
                                $this->output->writeln(sprintf('      - Document: %s', $item->getFilename()));

                                $document = new Document();
                                $document
                                    ->setNaam($item->getFilename())
                                    ->setFilename($item->getFilename())
                                    ->setMedewerker($medewerker)
                                    ->setFile(new FakeUploadedFile($item->getPathname(), $item->getFilename()))
                                ;

                                $deelnemer->addDocument($document);
                                $this->deelnemerDao->update($deelnemer);

                                $this->uploader->upload($document, 'file');
                            }
                        }
                    }
                }
            }
        }
    }

    private function findDeelnemerByDirname($dirname, $data)
    {
        try {
            $key = preg_replace('/\s/', '', $dirname);
            if (array_key_exists($key, $this->deelnemers)) {
                return $this->deelnemers[$key];
            }

            $deelnemer = $this->deelnemerDao->findOneByName($dirname);

            return $deelnemer;
        } catch (NoResultException $e) {
            // loopup RIS-number by name
            foreach ($data as $values) {
                $naam = sprintf('%s %s', $values[0], $values[1]);
                if ($naam === $dirname && $values[7]) {
                    $deelnemer = $this->entityManager->getRepository(Deelnemer::class)
                        ->findOneBy(['risNummer' => $values[7]]);
                    if ($deelnemer) {
                        return $deelnemer;
                    }
                }
            }
            $this->output->writeln('      - Documenten niet toegevoegd (vrijwilliger niet gevonden)');

            return null;
        } catch (NonUniqueResultException $e) {
            $this->output->writeln('      - Documenten niet toegevoegd (meerdere vrijwilliger gevonden)');

            return null;
        }
    }

    private function readData($file)
    {
        if (!file_exists($file)) {
            throw new \InvalidArgumentException(sprintf('File %s does not exist', $file));
        }

        if (!($handle = fopen($file, 'r'))) {
            throw new \InvalidArgumentException(sprintf('Cannot open file %s', $file));
        }

        $data = [];
        fgetcsv($handle, 0, ',');
        while ($values = fgetcsv($handle, 0, ',')) {
            $data[] = array_map('trim', $values);
        }
        fclose($handle);

        return $data;
    }

    private function processProjecten(array $data): array
    {
        $projecten = [];
        foreach ($data as $i => $row) {
            $projecten[$row[2]] = $row[3];
        }

        foreach ($projecten as $kpl => $naam) {
            $project = new Project();
            $project
                ->setNaam($naam)
                ->setKpl($kpl)
            ;
            $this->projectDao->create($project);
            $this->output->writeln(sprintf('  - Project toegevoegd: %s (%s)', $naam, $kpl));
            $projecten[$kpl] = $project;
        }

        return $projecten;
    }

    private function processDeelnemers(array $data, array $projecten): void
    {
        foreach ($data as $i => $values) {
            $klant = $this->findOrCreateKlant($values);
            $deelnemer = $this->findOrCreateDeelnemer($klant, $values);
            $beschikbaarheid = $this->processBeschikbaarheid($values);

            $project = $projecten[$values[2]];
            $deelname = new Deelname($deelnemer, $project);
            $deelname
                ->setActief((bool) $values[6])
                ->setBeschikbaarheid($beschikbaarheid)
            ;
            $deelnemer->addDeelname($deelname);

            $this->deelnemerDao->create($deelnemer);
            $this->deelnemers[preg_replace('/\s/', '', $values[0].$values[1])] = $deelnemer;
        }
    }

    private function findOrCreateKlant(array $values): Klant
    {
        try {
            // SCIP-match op naam
            $deelnemer = $this->deelnemerDao->findOneByName(sprintf('%s %s', $values[0], $values[1]));
            if ($deelnemer) {
                $this->output->writeln(sprintf('  - Vrijwilliger gevonden op naam: %s', $deelnemer->getKlant()));

                return $deelnemer->getKlant();
            }
        } catch (NoResultException $e) {
            // ignore
        }

        // match op RIS-nummer
        if ($values[7]) {
            $deelnemer = $this->entityManager->getRepository(\DagbestedingBundle\Entity\Deelnemer::class)->findOneBy([
                'risDossiernummer' => $values[7],
            ]);
            if ($deelnemer) {
                $this->output->writeln(sprintf('  - Vrijwilliger gevonden op RIS-nummer: %s (%s)', $deelnemer->getKlant(), $values[7]));

                return $deelnemer->getKlant();
            }
            $this->output->writeln(sprintf('  - Geen vrijwilliger gevonden voor RIS-nummer: %s', $values[7]));
        }

        // match op NAW-gegevens
        $klant = $this->entityManager->getRepository(Klant::class)->findOneBy([
            'voornaam' => $values[0],
            'achternaam' => $values[1],
            'email' => $values[8],
            'adres' => $values[9],
            'postcode' => $values[10],
            'plaats' => $values[11],
            'mobiel' => $values[12],
            'geboortedatum' => $values[13] ? new \DateTime($values[13]) : null,
        ]);

        if ($klant) {
            return $klant;
        }

        $klant = new Klant();
        $klant
            ->setVoornaam($values[0])
            ->setAchternaam($values[1])
            ->setEmail($values[8])
            ->setAdres($values[9])
            ->setPostcode($values[10])
            ->setPlaats($values[11])
            ->setMobiel($values[12])
            ->setGeboortedatum($values[13] ? new \DateTime($values[13]) : null)
            ->setLand($this->land)
            ->setNationaliteit($this->nationaliteit)
            ->setGeslacht($this->geslacht)
        ;

        $this->output->writeln(sprintf('    Nieuwe vrijwilliger toegevoegd: %s', $klant));

        return $klant;
    }

    private function findOrCreateDeelnemer(Klant $klant, array $values): Deelnemer
    {
        $deelnemer = $this->entityManager->getRepository(Deelnemer::class)->findOneBy([
            'klant' => $klant,
        ]);

        if ($deelnemer) {
            return $deelnemer;
        }

        $deelnemer = new Deelnemer($klant);
        $deelnemer
            ->setFunctie($values[4])
            ->setWerkbegeleider($values[5])
            ->setWerkbegeleider($values[5])
        ;

        if ($values[7]) {
            $deelnemer->setType(Deelnemer::TYPE_WMO)->setRisNummer($values[7]);
        }

        return $deelnemer;
    }

    private function processBeschikbaarheid(array $values): Beschikbaarheid
    {
        $beschikbaarheid = new Beschikbaarheid();
        $beschikbaarheid
            ->setMaandagVan($values[14] ? new \DateTime($values[14]) : null)
            ->setMaandagTot($values[15] ? new \DateTime($values[15]) : null)
            ->setDinsdagVan($values[16] ? new \DateTime($values[16]) : null)
            ->setDinsdagTot($values[17] ? new \DateTime($values[17]) : null)
            ->setWoensdagVan($values[18] ? new \DateTime($values[18]) : null)
            ->setWoensdagTot($values[19] ? new \DateTime($values[19]) : null)
            ->setDonderdagVan($values[20] ? new \DateTime($values[20]) : null)
            ->setDonderdagTot($values[21] ? new \DateTime($values[21]) : null)
            ->setVrijdagVan($values[22] ? new \DateTime($values[22]) : null)
            ->setVrijdagTot($values[23] ? new \DateTime($values[23]) : null)
            ->setZaterdagVan($values[24] ? new \DateTime($values[24]) : null)
            ->setZaterdagTot($values[25] ? new \DateTime($values[25]) : null)
            ->setZondagVan($values[26] ? new \DateTime($values[26]) : null)
            ->setZondagTot($values[27] ? new \DateTime($values[27]) : null);

        return $beschikbaarheid;
    }
}

class FakeUploadedFile extends UploadedFile
{
    private $test = false;
    private $originalName;
    private $mimeType;
    private $size;
    private $error;

    /**
     * Accepts the information of the uploaded file as provided by the PHP global $_FILES.
     *
     * The file object is only created when the uploaded file is valid (i.e. when the
     * isValid() method returns true). Otherwise the only methods that could be called
     * on an UploadedFile instance are:
     *
     *   * getClientOriginalName,
     *   * getClientMimeType,
     *   * isValid,
     *   * getError.
     *
     * Calling any other method on an non-valid instance will cause an unpredictable result.
     *
     * @param string      $path         The full temporary path to the file
     * @param string      $originalName The original file name of the uploaded file
     * @param string|null $mimeType     The type of the file as provided by PHP; null defaults to application/octet-stream
     * @param int|null    $size         The file size provided by the uploader
     * @param int|null    $error        The error constant of the upload (one of PHP's UPLOAD_ERR_XXX constants); null defaults to UPLOAD_ERR_OK
     * @param bool        $test         Whether the test mode is active
     *                                  Local files are used in test mode hence the code should not enforce HTTP uploads
     *
     * @throws FileException         If file_uploads is disabled
     * @throws FileNotFoundException If the file does not exist
     */
    public function __construct($path, $originalName, $mimeType = null, $size = null, $error = null, $test = false)
    {
        $this->originalName = $this->getName($originalName);
        $this->mimeType = $mimeType ?: 'application/octet-stream';
        $this->size = $size;
        $this->error = $error ?: UPLOAD_ERR_OK;
        $this->test = (bool) $test;

        parent::__construct($path, UPLOAD_ERR_OK === $this->error);
    }

    /**
     * Moves the file to a new location.
     *
     * @param string $directory The destination folder
     * @param string $name      The new file name
     *
     * @return File A File object representing the new file
     *
     * @throws FileException if, for any reason, the file could not have been moved
     */
    public function move($directory, $name = null)
    {
        $fs = new Filesystem();

        if ($this->isValid()) {
            if ($this->test) {
                return parent::move($directory, $name);
            }

            $target = $this->getTargetFile($directory, $name);

            set_error_handler(function ($type, $msg) use (&$error) { $error = $msg; });
            try {
                $fs->copy($this->getPathname(), $target);
                $moved = true;
            } catch (FileNotFoundException|IOException $e) {
                $moved = false;
            }
            restore_error_handler();
            if (!$moved) {
                throw new FileException(sprintf('Could not move the file "%s" to "%s" (%s)', $this->getPathname(), $target, strip_tags($error)));
            }

            @chmod($target, 0666 & ~umask());

            return $target;
        }

        throw new FileException($this->getErrorMessage());
    }

    /**
     * Returns whether the file was uploaded successfully.
     *
     * @return bool True if the file has been uploaded with HTTP and no error occurred
     */
    public function isValid()
    {
        $isOk = UPLOAD_ERR_OK === $this->error;

        return $isOk;
    }
}
