<?php

namespace AppBundle\Service;

use AppBundle\Entity\Klant;
use AppBundle\Exception\AppException;
use AppBundle\Filter\FilterInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;

class KlantDao extends AbstractDao implements KlantDaoInterface, DoelstellingDaoInterface
{
    const DUPLICATE_MODE_SAME_BIRTHDAY = 'same_birthday';
    const DUPLICATE_MODE_UNKNOWN_BIRTHDAY = 'unknown_birthday';
    const DUPLICATE_MODE_SAME_SURNAME = 'same_surname';
    const DUPLICATE_MODE_SURNAME_LIKE_FIRSTNAME = 'surname_like_firstname';

    protected $paginationOptions = [
        'defaultSortFieldName' => 'klant.achternaam',
        'defaultSortDirection' => 'asc',
        'sortFieldWhitelist' => [
            'klant.id',
            'klant.voornaam',
            'klant.achternaam',
            'klant.geboortedatum',
            'medewerker.voornaam',
            'geslacht.volledig',
            'werkgebied.naam',
            'postcodegebied.naam',
        ],
        'wrap-queries' => true, // because of HAVING clause in filter
    ];

    protected $class = Klant::class;

    protected $alias = 'klant';

    /**
     * @param int             $page
     * @param FilterInterface $filter
     *
     * @return PaginationInterface
     */
    public function findAll($page = null, FilterInterface $filter = null)
    {
        $builder = $this->getAllQueryBuilder($filter);
        if ($page) {
            return $this->paginator->paginate($builder, $page, $this->itemsPerPage, $this->paginationOptions);
        }

        return $builder->getQuery()->getResult();
    }

    public function getAllQueryBuilder(FilterInterface $filter = null)
    {
        $builder = $this->repository->createQueryBuilder($this->alias)
            ->leftJoin("{$this->alias}.medewerker", 'medewerker')
            ->leftJoin("{$this->alias}.geslacht", 'geslacht')
            ->leftJoin("{$this->alias}.werkgebied", 'werkgebied')
            ->leftJoin("{$this->alias}.postcodegebied", 'postcodegebied')
        ;

        if ($filter) {
            $filter->applyTo($builder);
        }

        return $builder;
    }

    /**
     * @param FilterInterface $filter
     *
     * @return int
     */
    public function countAll(FilterInterface $filter = null)
    {
        $builder = $this->repository->createQueryBuilder($this->alias)
            ->select("COUNT({$this->alias}.id)")
            ->orderBy("{$this->alias}.achternaam")
        ;

        if ($filter) {
            $filter->applyTo($builder);
        }

        return $builder->getQuery()->getSingleScalarResult();
    }


    /**
     * @param int $id
     *
     * @return Klant
     */
    public function find($id)
    {
        return parent::find($id);
    }

    /**
     * @param Klant $klant
     */
    public function create(Klant $klant)
    {
        return $this->doCreate($klant);
    }

    /**
     * @param Klant $klant
     */
    public function update(Klant $klant)
    {
        return $this->doUpdate($klant);
    }

    /**
     * @param Klant $klant
     */
    public function delete(Klant $klant)
    {
        // @todo remove this when disabled field is no longer needed
        $klant->setDisabled(true);
        $this->update($klant);

        return $this->doDelete($klant);
    }

    public function findDuplicates($mode)
    {
        switch ($mode) {
            case self::DUPLICATE_MODE_UNKNOWN_BIRTHDAY:
                $pairs = $this->entityManager->getConnection()->fetchAll("
                    SELECT k1.id id1, k2.id id2, k1.geboortedatum,
                        CONCAT_WS(' ', k1.voornaam, k1.roepnaam, k1.achternaam) AS name1,
                        CONCAT_WS(' ', k2.voornaam, k2.roepnaam, k2.achternaam) AS name2
                    FROM klanten AS k1
                    INNER JOIN klanten AS k2
                    ON (k1.geboortedatum = k2.geboortedatum AND k1.id > k2.id)
                    WHERE k1.disabled = 0
                    AND k2.disabled = 0
                    AND (k1.geboortedatum IS NULL OR k1.geboortedatum = '1970-01-01')
                    ORDER BY k1.achternaam
                ");
                $matchedKey = 'geboortedatum';
                break;

            case self::DUPLICATE_MODE_SAME_BIRTHDAY:
                $pairs = $this->entityManager->getConnection()->fetchAll("
                    SELECT k1.id id1, k2.id id2, k1.geboortedatum,
                        DATE_FORMAT(k1.geboortedatum, '%e %b %Y') AS formatted_date,
                        CONCAT_WS(' ', k1.voornaam, k1.roepnaam, k1.achternaam) AS name1,
                        CONCAT_WS(' ', k2.voornaam, k2.roepnaam, k2.achternaam) AS name2
                    FROM klanten AS k1
                    INNER JOIN klanten AS k2
                    ON (k1.geboortedatum = k2.geboortedatum AND k1.id > k2.id)
                    WHERE k1.disabled = 0
                    AND k2.disabled = 0
                    AND k1.geboortedatum IS NOT NULL AND k1.geboortedatum != '1970-01-01'
                    ORDER BY k1.geboortedatum
                ");
                $matchedKey = 'formatted_date';
                break;

            case self::DUPLICATE_MODE_SAME_SURNAME:
                $pairs = $this->entityManager->getConnection()->fetchAll("
                        select k1.id id1, k2.id id2, k1.achternaam, concat_ws(' ', k1.voornaam, k1.roepnaam, k1.achternaam) name1, concat_ws(' ', k2.voornaam, k2.roepnaam, k2.achternaam) name2, k1.geboortedatum
                        from klanten k1
                        join klanten k2
                        on (k1.id > k2.id and trim(k1.achternaam) = trim(k2.achternaam))
                        where k1.disabled = 0
                        and k2.disabled = 0
                        order by k1.achternaam
                        ");
                $matchedKey = 'achternaam';
                break;

            case self::DUPLICATE_MODE_SURNAME_LIKE_FIRSTNAME:
                $pairs = $this->entityManager->getConnection()->fetchAll("
                    SELECT k1.id id1, k2.id id2, k2.achternaam, k1.geboortedatum,
                        CONCAT_WS(' ', k1.voornaam, k1.roepnaam, k1.achternaam) AS name1,
                        CONCAT_WS(' ', k2.voornaam, k2.roepnaam, k2.achternaam) AS name2
                    FROM klanten k1
                    INNER JOIN klanten k2
                    ON (k2.id != k1.id
                        AND k2.achternaam != ''
                        AND (TRIM(k1.voornaam) = TRIM(k2.achternaam))
                        OR (TRIM(k1.roepnaam) = TRIM(k2.achternaam))
                    )
                    WHERE k1.disabled = 0
                    AND k2.disabled = 0
                    AND k1.id != k2.id
                    ORDER BY k2.achternaam
                ");
                $matchedKey = 'achternaam';
                break;

            case 'less_relaxed_surname':
                $pairs = $this->query("
                    SELECT k1.id id1, k2.id id2, k2.achternaam, concat_ws(' ', k1.voornaam, k1.roepnaam, k1.achternaam) name1, concat_ws(' ', k2.voornaam, k2.roepnaam, k2.achternaam) name2, k1.geboortedatum
                    FROM klanten AS k1
                    INNER JOIN klanten AS k2
                    ON (
                        (TRIM(k1.voornaam) = TRIM(k2.achternaam) AND (SUBSTR(k1.achternaam, 1, 1) = SUBSTR(k2.voornaam, 1, 1)) OR SUBSTR(k1.achternaam, 1, 1) = SUBSTR(k2.roepnaam, 1, 1)))
                        OR
                        (TRIM(k1.roepnaam) = TRIM(k2.achternaam) AND (SUBSTR(k1.achternaam, 1, 1) = SUBSTR(k2.roepnaam, 1, 1) OR  SUBSTR(k1.achternaam, 1, 1) = SUBSTR(k2.voornaam, 1, 1))
                    )
                    WHERE k1.disabled = 0
                    AND k2.disabled = 0
                    ORDER BY name1
                ");
                $matchedKey = 'achternaam';
                break;

            default:
                throw new AppException('Undefined mode '.$mode);
        }

        $this->sets = [];
        $this->setIndexes = [];

        foreach ($pairs as $pair) {
            $id1 = $pair['id1'];
            $id2 = $pair['id2'];
            $match = $pair[$matchedKey];
            $name1 = $pair['name1'];
            $name2 = $pair['name2'];

            $this->addPair($id1, $id2, $match, $name1, $name2);
        }

        return $this->sets;
    }

    private function addPair($id1, $id2, $match, $name1, $name2)
    {
        if (!empty($this->setIndexes[$id2]) && !empty($this->setIndexes[$id1])) {
            return;
        } elseif (!empty($this->setIndexes[$id1])) {
            $index = $this->setIndexes[$id1];
            $this->sets[$index]['ids'][] = $id2;
            $this->sets[$index]['klanten'][] = $name2;
            $this->setIndexes[$id2] = $index;
        } elseif (!empty($this->setIndexes[$id2])) {
            $index = $this->setIndexes[$id2];
            $this->sets[$index]['ids'][] = $id1;
            $this->sets[$index]['klanten'][] = $name1;
            $this->setIndexes[$id1] = $index;
        } else {
            $index = count($this->sets) + 1;
            $this->sets[$index] = [
                'match' => $match,
                'ids' => [$id1, $id2],
                'klanten' => [$name1, $name2],
            ];
            $this->setIndexes[$id1] = $index;
            $this->setIndexes[$id2] = $index;
        }
    }

    public function getKpis(): array
    {
//        $sql = $builder->getQuery()->getSQL();
//        $params = $builder->getQuery()->getParameters();

        return ["Aap"=>"0",1=>"noot",2=>"mies"];
    }

    public static function getPrestatieLabel(): string
    {
        return "Klanten";
    }
}
