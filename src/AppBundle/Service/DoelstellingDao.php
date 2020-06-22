<?php

namespace AppBundle\Service;

use AppBundle\Entity\Land;
use AppBundle\Entity\Doelstelling;
use AppBundle\Filter\DoelstellingFilter;
use AppBundle\Filter\FilterInterface;
use AppBundle\Repository\DoelstellingRepositoryInterface;
use Psr\Container\ContainerInterface;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class DoelstellingDao extends AbstractDao
{
    protected $paginationOptions = [
        'defaultSortFieldName' => 'repository',
        'defaultSortDirection' => 'asc',
        'sortFieldWhitelist' => [
            'repository',
            'categorie',
            'kpl',
        ],
    ];

    protected $class = Doelstelling::class;

    protected $alias = 'doelstelling';

    /**
     * @var ContainerInterface $serviceContainer
     */
    protected $serviceContainer;

    /**
     * @var AccessDecisionManagerInterface $decisionManager;
     */
    protected $decisionManager;

    /**
     * @param ContainerInterface $container
     * @required
     */
    public function setServiceContainer(ContainerInterface $container)
    {
        $this->serviceContainer = $container;
    }

    /**
     * Sets decision manager via service definition. This way we can decide if user has access to certain doelstellingen.
     * @param AccessDecisionManagerInterface $decisionManager
     * @required
     */
    public function setDecisionManager(AccessDecisionManagerInterface $decisionManager)
    {
        $this->decisionManager = $decisionManager;
    }

    public function getAvailableDoelstellingcijfers($doelstellingRepo,$onlyAvailableOptions=true)
    {
        $builder = $this->repository->createQueryBuilder($this->alias);
        $vastgelegdeCijfers = $builder->getQuery()->getResult();
        $cijfers = $doelstellingRepo->getAvailableDoelstellingcijfers();
        if($onlyAvailableOptions!==true) return $cijfers;
        foreach ($cijfers as $k=>$cijfer) {

            $a = $cijfer;
            $fullMethodRepoName = get_class($doelstellingRepo)."::".$cijfer->getLabel();
            foreach ($vastgelegdeCijfers as $dbCijfer) {
                if($dbCijfer->getRepository() == $fullMethodRepoName) unset($cijfers[$k]);

            }
      }
        return $cijfers;
            
    }
    public function findAll($page = null, FilterInterface $filter = null)
    {
        $builder = $this->repository->createQueryBuilder($this->alias);

        if ($filter) {
            $filter->applyTo($builder);
        }

//        if ($page) {
//            $paginatedResult = $this->paginator->paginate($builder, $page, $this->itemsPerPage, $this->paginationOptions);
//        }

        $result =  $builder->getQuery()->getResult();

        //Results should be connected to the repositories used.
        $result = $this->connectDoelstellingenInterfaceReposToResult($result,$filter);
        if($page)
        {
            return $this->paginator->paginate($result,$page,$this->itemsPerPage,$this->paginationOptions);
        }
        return $result;


    }

    /**
     * This method connects the default doelstellingen results with the calculcated response from all the doelstellingen repositories.
     * It also takes into account if the user has the proper rights to view those results.
     * @param $doelstellingen
     * @return mixed
     */
    private function connectDoelstellingenInterfaceReposToResult($doelstellingen,$filter = null)
    {
        $token = $this->serviceContainer->get("security.token_storage")->getToken();
        $user = $token->getUser();
        $roles = $token->getRoles();

        $hasRoleDoelstellingenBeheer = $this->serviceContainer->get("security.authorization_checker")->isGranted("ROLE_DOELSTELLING_BEHEER");
        $hasRoleAdmin = $this->serviceContainer->get("security.authorization_checker")->isGranted("ROLE_ADMIN");

        $fullAccess = ($hasRoleDoelstellingenBeheer || $hasRoleAdmin);

        foreach($doelstellingen as $k=>$row)
        {
            /**
             * @var Doelstelling $row
             */
            $repos = $row->getRepository();

            $roleName = self::getRoleNameForRepositoryMethod($repos); //get Rolename for this repository to vote if user has access to this repository.

            $canView = $this->decisionManager->decide($token,[$roleName]);
            if(!$canView && !$fullAccess)
            {
                unset($doelstellingen[$k]);
                continue;
            }
            $startdatum = $einddatum = null;
            if($filter instanceof DoelstellingFilter)
            {
                $startdatum = $filter->startdatum;
                $einddatum = $filter->einddatum;
                $numberOfDays = $einddatum->diff($startdatum)->format("%a");
                $percentage = $numberOfDays/365;
                $row->setRelativeAantal(ceil($row->getAantal() * $percentage));
            }

            list($class,$method) = explode("::",$repos);
            if(!$class || !$method) throw new Exception("Repository incorrect. Cannot retrieve doelstelling data from repository for $repos");
            try {
                /**
                 * @var DoelstellingRepositoryInterface $r
                 */
                $r = $this->serviceContainer->get($class);
                $doelstellingcijfer = $r->getDoelstelingcijfer($method);

                $row->setKostenplaats($doelstellingcijfer->getKpl());
                $row->setRepositoryLabel($doelstellingcijfer->getLabel());


                $c = $doelstellingcijfer->getClosure();
                $n = $c($row,$startdatum,$einddatum);

            }
            catch(Exception $e)
            {
                $number = "!";
            }

            $row->setActueel($n);
//            $row->setRepositoryLabel($method);
        }

        return $doelstellingen;

    }

    /**
     * @param int $id
     *
     * @return Land
     */
    public function find($id)
    {
        return parent::find($id);
    }

    /**
     * @param Doelstelling $prestatie
     */
    public function create(Doelstelling $prestatie)
    {
        return $this->doCreate($prestatie);
    }

    /**
     *@param Doelstelling $prestatie
     */
    public function update(Doelstelling $prestatie)
    {
        return $this->doUpdate($prestatie);
    }

    /**
     * @param Doelstelling $prestatie
     */
    public function delete(Doelstelling $prestatie)
    {
        return $this->doDelete($prestatie);
    }

    private static function getBundleName($repositoryMethodString)
    {
        $matches = [];
        $re = '/(.*)Bundle\\\\(.*)\\\\(.*)::(.*)/';
        preg_match($re, $repositoryMethodString, $matches);

        if (5 !== count($matches)) {
            throw new \BadFunctionCallException('Could not determine proper bundle name. Should provide valid repository methodstring: Namespace\BundleName\Class::Method');
        }

        return sprintf('%s', strtoupper($matches[1]));
    }

    public static function getRoleNameForRepositoryMethod($repositoryMethodString)
    {
        return "ROLE_".self::getBundleName($repositoryMethodString);//."_BEHEER";
    }
}
