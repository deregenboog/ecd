<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Medewerker;
use AppBundle\Export\AbstractExport;
use AppBundle\Form\MedewerkerEditType;
use AppBundle\Form\MedewerkerFilterType;
use AppBundle\Service\MedewerkerDaoInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/medewerkers")
 *
 * @Template
 *
 * @IsGranted("ROLE_USERADMIN")
 */
class MedewerkersController extends AbstractController
{
    protected $entityName = 'medewerker';
    protected $entityClass = Medewerker::class;
    protected $filterFormClass = MedewerkerFilterType::class;
    protected $formClass = MedewerkerEditType::class;
    protected $baseRouteName = 'app_medewerkers_';
    protected $diablesActions = ['add'];

    /**
     * @var MedewerkerDaoInterface
     */
    protected $dao;

    /**
     * @var array
     */
    protected $roleHierarchy;

    /**
     * @var AbstractExport
     */
    protected $export;

    public function __construct(MedewerkerDaoInterface $dao, array $roleHierarchy, AbstractExport $export)
    {
        $this->dao = $dao;
        $this->roleHierarchy = $roleHierarchy;
        $this->export = $export;
    }

    /**
     * @Route("/{id}/view")
     *
     * @Template
     */
    public function viewAction(Request $request, $id)
    {
        $entity = $this->dao->find($id);

        return [
            'entity' => $entity,
            'role_hierarchy' => $this->roleHierarchy,
        ];
    }

    /**
     * @Route("/{id}/delete")
     *
     * @param int $id
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request, $id)
    {
        $this->forceRedirect = true;

        return parent::deleteAction($request, $id);
    }
}
