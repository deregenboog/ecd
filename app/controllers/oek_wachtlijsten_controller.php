<?php

use AppBundle\Entity\Klant;
use OekBundle\Entity\OekKlant;
use OekBundle\Form\Model\OekKlantModel;
use OekBundle\Form\OekKlantGroepType;
use OekBundle\Form\OekKlantTrainingType;
use OekBundle\Form\OekKlantType;
use AppBundle\Form\KlantFilterType;
use OekBundle\Form\OekKlantSelectType;
use Doctrine\DBAL\Driver\PDOException;
use OekBundle\Form\OekKlantFilterType;
use AppBundle\Form\ConfirmationType;
use AppBundle\Entity\Medewerker;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class OekWachtlijstenController extends AppController
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
        'id',
        'klant' => ['naam', 'stadsdeel'],
//        'groepen',
        'aanmelding',
        'afsluiting'
    ];

    private $sortFieldWhitelist = [
        'oekKlant.id',
        'klant.achternaam',
        'klant.werkgebied',
//        'oekKlant.groepen',
        'oekKlant.aanmelding',
        'oekKlant.afsluiting',
    ];

    public function index()
    {
        $filter = $this->createForm(OekKlantFilterType::class, null, [
            'enabled_filters' => $this->enabledFilters,
        ]);
        $filter->handleRequest($this->request);

        $entityManager = $this->getEntityManager();
        $repository = $entityManager->getRepository(OekKlant::class);

        $builder = $repository->createQueryBuilder('oekKlant')
            ->innerJoin('oekKlant.klant', 'klant')
//            ->innerJoin('oekKlant.oekGroepen', 'groepen')
            ->andWhere('klant.disabled = false')
        ;

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
}
