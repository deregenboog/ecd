<?php

namespace MwBundle\Command;

use AppBundle\Entity\Klant;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\Result;
use Doctrine\ORM\EntityManager;
use Knp\Component\Pager\Paginator;
use MwBundle\Entity\Aanmelding;
use MwBundle\Entity\Afsluiting;
use MwBundle\Entity\AfsluitredenKlant;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

class CloseOldDossiers extends \Symfony\Component\Console\Command\Command
{
    /**
     * @var EntityManager
     */
    private $manager;


    public function __construct()
    {
        $this->manager = $this->getContainer()->get('doctrine')->getManager();

        parent::__construct();
    }

    protected function configure()
    {
        $this->setName('mw:dossierstatus:closeold');
        $this->addUsage("Closes old dossiers. Old is when last verslag is ouder dan 12 maanden.");
        $this->addOption('dry-run');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        $mwKlanten = $this->getMwKlantenWithOldVerslagen();

        $output->writeln(sprintf('%d MwKlanten gevonden', count($mwKlanten)) );

        $afsluitredenRepo = $this->manager->getRepository(AfsluitredenKlant::class);
        $afsluitreden = $afsluitredenRepo->findOneBy(["naam"=>"Client uit beeld"]);
        if($afsluitreden == false)
        {
            throw new Exception("Cannot find afsluitreden");
        }



        foreach ($mwKlanten as $klant) {

            /**
             * Klant $klant
             */
            $klant;

            $output->writeln(sprintf("Bezig met klant %s",$klant->getId() ) );

            $afsluiting = new Afsluiting($klant);
            $afsluiting->setReden($afsluitreden);
            $klant->setHuidigeMwStatus($afsluiting);



        }
        if(!$input->getOption('dry-run')) {
            $this->manager->flush();
        }
        $output->writeln('Succesvol afgerond');
        return 0;
    }



    private function getMwKlantenWithOldVerslagen()
    {
        $fromDate = new \DateTime("now");
        $fromDate->sub(new \DateInterval("P1Y"));

        $builder = $this->manager->getRepository(Klant::class)->createQueryBuilder('klant');
        $query = "(
        SELECT k.id AS klantId, v.datum AS laatsteVerslagDatum
        FROM klanten AS k
            INNER JOIN verslagen AS v ON k.id = v.klant_id
            INNER JOIN mw_dossier_statussen mds on k.huidigeMwStatus_id = mds.id and k.id = mds.klant_id
        WHERE (k.id, v.datum) IN
                  (
                  SELECT v.klant_id, MAX(v.datum) AS maxDate FROM verslagen AS v
                  GROUP BY v.klant_id
                  HAVING (maxDate <= :fromdate)
                )
        AND mds.class = :aanmelding
        GROUP BY k.id -- more than one verslag on same date
        ORDER BY v.datum DESC)";


        $conn = $this->manager->getConnection();
        $statement = $conn->prepare($query);
        $statement->bindValue("fromdate",$fromDate->format("Y-m-d"),ParameterType::STRING);
        $statement->bindValue("aanmelding","Aanmelding");


        $result = $statement->executeQuery();
        $klantIds = $result->fetchFirstColumn();

        $builder->select("klant")
            ->where("klant.id IN (:klantIds)")
           ->setMaxResults(3000)
            ->setParameter("klantIds",$klantIds)
        ;

        return $builder->getQuery()->getResult();


    }

}
