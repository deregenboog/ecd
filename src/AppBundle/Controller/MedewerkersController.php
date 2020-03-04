<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Medewerker;
use AppBundle\Form\MedewerkerFilterType;
use AppBundle\Service\MedewerkerDaoInterface;
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
     */
    protected $dao;

    /**
     * @var MedewerkerDaoInterface
     */
    protected $roleHierarchy;

    public function setContainer(\Psr\Container\ContainerInterface $container): ?\Psr\Container\ContainerInterface
    {
        $previous = parent::setContainer($container);

        $this->dao = $container->get("AppBundle\Service\MedewerkerDao");
        $this->roleHierarchy = $container->getParameter("security.role_hierarchy.roles");

        return $previous;
    }

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
