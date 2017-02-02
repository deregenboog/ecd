<?php

use AppBundle\Entity\Klant;
use OekBundle\Entity\OekKlant;
use OekBundle\Form\Model\OekKlantFacade;
use OekBundle\Form\OekKlantAddGroepType;
use OekBundle\Form\OekKlantAddTrainingType;
use OekBundle\Form\OekKlantType;
use AppBundle\Form\KlantFilterType;
use OekBundle\Form\OekKlantSelectType;
use Doctrine\DBAL\Driver\PDOException;
use OekBundle\Form\OekKlantFilterType;
use AppBundle\Form\ConfirmationType;
use AppBundle\Entity\Medewerker;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;

class OekWachtlijstController extends AppController
{
    /**
     * Don't use CakePHP models.
     */
    public $uses = [];

    /**
     * Use Twig.
     */
    public $view = 'AppTwig';

    private $enabledFilters = [
        'klant' => ['id', 'naam', 'stadsdeel'],
        'aanmelding',
        'afsluiting',
        'groep',
    ];

    private $sortFieldWhitelist = [
        'klant.id',
        'klant.achternaam',
        'klant.werkgebied',
        'oekKlant.aanmelding',
        'oekKlant.afsluiting',
        'oekKlant.groepen',
    ];

    public function index()
    {
        $repository = $this->getEntityManager()->getRepository(OekKlant::class);
        $builder = $repository->createQueryBuilder('oekKlant')
            ->innerJoin('oekKlant.klant', 'klant')
            ->innerJoin('oekKlant.oekGroepen', 'oekGroep')
            ->andWhere('klant.disabled = false')
        ;

        $filter = $this->createFilter();
        if ($filter->isValid()) {
            $filter->getData()->applyTo($builder);
        }

        $pagination = $this->getPaginator()->paginate($builder, $this->request->get('page', 1), 20, [
            'defaultSortFieldName' => 'klant.achternaam',
            'defaultSortDirection' => 'asc',
            'sortFieldWhitelist' => $this->sortFieldWhitelist,
            'wrap-queries' => true, // because of HAVING clause in filter
        ]);

        $this->set('filter', $filter->createView());
        $this->set('pagination', $pagination);
    }

    /**
     * @return FormInterface
     */
    private function createFilter()
    {
        $filter = $this->createForm(OekKlantFilterType::class, null, [
            'enabled_filters' => $this->enabledFilters,
        ]);
        $filter->handleRequest($this->request);

        return $filter;
    }
}
