<?php

namespace IzBundle\Repository;

use AppBundle\Entity\Doelstelling;
use AppBundle\Model\Doelstellingcijfer;
use AppBundle\Repository\DoelstellingRepositoryInterface;
use AppBundle\Repository\DoelstellingRepositoryTrait;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use IzBundle\Entity\Hulpvraag;
use IzBundle\Entity\Project;

class ProjectRepository extends EntityRepository implements DoelstellingRepositoryInterface
{
    use DoelstellingRepositoryTrait;
    /**
     * Finds all projects that are active on the given date.
     *
     * @param \DateTime $date
     *
     * @return array
     */
    public function findActive(\DateTime $date = null)
    {
        $builder = $this->createQueryBuilder('p')
            ->where('p.startdatum <= :date')
            ->andWhere('p.einddatum IS NULL OR p.einddatum > :date')
            ->orderBy('p.naam', 'ASC')
            ->setParameter('date', $date ?: new \DateTime())
        ;

        return $builder->getQuery()->getResult();
    }

    public function getMethods(): array
    {
        return [];
    }
    public function getCategory(): string
    {
        return DoelstellingRepositoryInterface::CAT_IZ;
    }

    public function initDoelstellingcijfers(): void
    {
//        $this->cijfers[] = new Doelstellingcijfer("")
        $this->cijfers = array();
    }

    public static function getCategoryLabel():string
    {
        return "Informele Zorg";
    }

    public function getGoals($type, $doelstellingen)
    {
        $years = [];
        //from all doelstellingen, get the min and max years so we only get those results of years asked for
        foreach ($doelstellingen as $doelstelling) {
            $years[] = $doelstelling->getJaar();

        }
        $years = array_unique($years);

        $minYear = min($years);
        $maxYear = max($years);

        /*
         * Projecten is asked but is counted by hulpvragen via koppelingen...
         */
        $hulpvraagRepos = $this->getEntityManager()->getRepository(Hulpvraag::class);


        $koppelingenPerProject = $hulpvraagRepos->countKoppelingenByProjectId($type,new \DateTime("$minYear-01-01"),new \DateTime("$maxYear-12-31"));

        return $koppelingenPerProject;
    }


}
