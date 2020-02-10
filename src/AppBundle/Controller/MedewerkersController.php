<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Medewerker;
use AppBundle\Form\MedewerkerFilterType;
use AppBundle\Service\MedewerkerDaoInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/medewerkers")
 * @Template
 */
class MedewerkersController extends AbstractController
{
    protected $title = 'Medewerkers';
    protected $entityName = 'medewerker';
    protected $entityClass = Medewerker::class;
    protected $filterFormClass = MedewerkerFilterType::class;
    protected $baseRouteName = 'app_medewerkers_';
    protected $diablesActions = ['add', 'edit', 'delete'];

    /**
     * @var MedewerkerDaoInterface
     *
     * @DI\Inject("AppBundle\Service\MedewerkerDao")
     */
    protected $dao;

    /**
     * @var MedewerkerDaoInterface
     *
     * @DI\Inject("%security.role_hierarchy.roles%")
     */
    protected $roleHierarchy;

    /**
     * @Route("/{username}/view")
     * @Template
     */
    public function viewAction(Request $request, $username)
    {
        $entity = $this->dao->find($username);

        return [
            'entity' => $entity,
            'role_hierarchy' => $this->roleHierarchy,
        ];
    }
}
