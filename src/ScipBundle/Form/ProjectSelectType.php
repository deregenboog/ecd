<?php

namespace ScipBundle\Form;

use AppBundle\Entity\Medewerker;
use AppBundle\Form\BaseSelectType;
use Doctrine\ORM\EntityRepository;
use ScipBundle\Entity\Project;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;

class ProjectSelectType extends AbstractType
{
    /**
     * @var Medewerker
     */
    private $medewerker;

    /**
     * @var \Symfony\Component\Security\Core\Role\RoleInterface[]
     */
    private $roles;

    /**
     * @var AuthorizationChecker
     */
    private $securityAuthorizationChecker;

    public function __construct(TokenStorageInterface $tokenStorage, AuthorizationChecker $securityAuthorizationChecker)
    {
        $this->medewerker = $tokenStorage->getToken()->getUser();
        $this->roles = $tokenStorage->getToken()->getRoles();
        $this->securityAuthorizationChecker = $securityAuthorizationChecker;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'class' => Project::class,
            'preset' => false,
            'query_builder' => function (Options $options) {
                return function (EntityRepository $repository) {
                    $builder = $repository->createQueryBuilder('projecten')
                        ->select('projecten')
                        ->leftJoin('projecten.toegangsrechten', 'toegang')

                        ->orderBy('projecten.naam');
                    if ($this->medewerker && !$this->securityAuthorizationChecker->isGranted('ROLE_SCIP_BEHEER')) {
                        $builder
                            ->where('toegang.medewerker = :medewerker')
                            ->setParameter('medewerker', $this->medewerker->getId());
                    }

                    return $builder;
                };
            },
            ]);
    }

    public function getParent(): ?string
    {
        return BaseSelectType::class;
    }
}
