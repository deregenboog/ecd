<?php

namespace AppBundle\Service;

use AppBundle\Entity\Doelstelling;
use AppBundle\Filter\DoelstellingFilter;
use AppBundle\Filter\FilterInterface;
use AppBundle\Repository\DoelstellingRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
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

    protected AccessDecisionManagerInterface $decisionManager;

    protected TokenStorageInterface $tokenStorage;

    protected AuthorizationCheckerInterface $authorizationChecker;

    private iterable $repositories;

    public function __construct(
        AccessDecisionManagerInterface $decisionManager,
        TokenStorageInterface $tokenStorage,
        AuthorizationCheckerInterface $authorizationChecker,
        iterable $repositories,
        EntityManagerInterface $entityManager,
        ?PaginatorInterface $paginator = null,
        $itemsPerPage = 10
    ) {
        $this->decisionManager = $decisionManager;
        $this->tokenStorage = $tokenStorage;
        $this->authorizationChecker = $authorizationChecker;
        $this->repositories = $repositories;

        parent::__construct($entityManager, $paginator, $itemsPerPage);
    }

    public function getAvailableDoelstellingcijfers($doelstellingRepo, $onlyAvailableOptions = true)
    {
        $builder = $this->repository->createQueryBuilder($this->alias);
        $vastgelegdeCijfers = $builder->getQuery()->getResult();
        $cijfers = $doelstellingRepo->getAvailableDoelstellingcijfers();
        if (true !== $onlyAvailableOptions) {
            return $cijfers;
        }

        foreach ($cijfers as $i => $cijfer) {
            $fullMethodRepoName = get_class($doelstellingRepo).'::'.$cijfer->getLabel();
            foreach ($vastgelegdeCijfers as $dbCijfer) {
                if ($dbCijfer->getRepository() == $fullMethodRepoName) {
                    unset($cijfers[$i]);
                }
            }
        }

        return $cijfers;
    }

    public function findAll($page = null, ?FilterInterface $filter = null)
    {
        $builder = $this->repository->createQueryBuilder($this->alias);

        if ($filter) {
            $filter->applyTo($builder);
        }

        // if ($page) {
        //     $paginatedResult = $this->paginator->paginate($builder, $page, $this->itemsPerPage, $this->paginationOptions);
        // }

        $result = $builder->getQuery()->getResult();

        // Results should be connected to the repositories used.
        $result = $this->connectDoelstellingenInterfaceReposToResult($result, $filter);

        if ($page) {
            return $this->paginator->paginate($result, $page, $this->itemsPerPage, $this->paginationOptions);
        }

        return $result;
    }

    /**
     * This method connects the default doelstellingen results with the calculcated response from all the doelstellingen repositories.
     * It also takes into account if the user has the proper rights to view those results.
     */
    private function connectDoelstellingenInterfaceReposToResult($doelstellingen, $filter = null)
    {
        $token = $this->tokenStorage->getToken();
        $hasRoleDoelstellingenBeheer = $this->authorizationChecker->isGranted('ROLE_DOELSTELLING_BEHEER');
        $hasRoleAdmin = $this->authorizationChecker->isGranted('ROLE_ADMIN');
        $fullAccess = ($hasRoleDoelstellingenBeheer || $hasRoleAdmin);

        foreach ($doelstellingen as $i => $doelstelling) {
            /** @var Doelstelling $doelstelling */

            $source = $doelstelling->getRepository();
            $roleName = self::getRoleNameForRepositoryMethod($source); // get Rolename for this repository to vote if user has access to this repository.
            $canView = $this->decisionManager->decide($token, [$roleName]);
            if (!$canView && !$fullAccess) {
                unset($doelstellingen[$i]);
                continue;
            }

            $startdatum = $einddatum = null;
            if ($filter instanceof DoelstellingFilter) {
                $startdatum = $filter->startdatum;
                $einddatum = $filter->einddatum;
                $numberOfDays = $einddatum->diff($startdatum)->format('%a');
                $percentage = $numberOfDays / 365;
                $doelstelling->setRelativeAantal(ceil($doelstelling->getAantal() * $percentage));
            }

            [$class, $method] = explode('::', $source);
            if (!$class || !$method) {
                throw new \Exception("Repository incorrect. Cannot retrieve doelstelling data from repository for $source");
            }

            try {
                /** @var DoelstellingRepositoryInterface $repository */
                $repository = null;
                foreach ($this->repositories as $repository) {
                    if ($repository instanceof $class) {
                        break;
                    }
                }

                $doelstellingcijfer = $repository->getDoelstelingcijfer($method);
                $doelstelling->setKostenplaats($doelstellingcijfer->getKpl());
                $doelstelling->setRepositoryLabel($doelstellingcijfer->getLabel());

                $c = $doelstellingcijfer->getClosure();
                $n = $c($doelstelling, $startdatum, $einddatum);
            } catch (\Exception $e) {
                $n = '!';
            }

            $doelstelling->setActueel($n);
        }

        return $doelstellingen;
    }

    public function find($id): ?Doelstelling
    {
        return parent::find($id);
    }

    public function create(Doelstelling $prestatie)
    {
        return $this->doCreate($prestatie);
    }

    public function update(Doelstelling $prestatie)
    {
        return $this->doUpdate($prestatie);
    }

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
        return 'ROLE_'.self::getBundleName($repositoryMethodString); // ."_BEHEER";
    }
}
