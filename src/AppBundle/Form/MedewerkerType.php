<?php

namespace AppBundle\Form;

use AppBundle\Entity\Medewerker;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Role\Role;
use Symfony\Component\Security\Core\Role\RoleHierarchy;

class MedewerkerType extends AbstractType
{
    /**
     * @var array
     */
    private $roles = [];

    public function __construct(RoleHierarchy $roleHierarchy, $maxRole)
    {
        // get all roles
        $reachableRoles = $roleHierarchy->getReachableRoles([new Role($maxRole)]);
        foreach ($reachableRoles as $role) {
            if ('ROLE_' === substr($role->getRole(), 0, 5)) {
                $label = ucfirst(strtolower(substr($role->getRole(), 5)));
                $this->roles[$label] = $role->getRole();
            }
        }
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('actief')
            ->add('username')
            ->add('email')
            ->add('voornaam')
            ->add('tussenvoegsel')
            ->add('achternaam')
            ->add('roles', ChoiceType::class, [
                'expanded' => true,
                'multiple' => true,
                'choices' => $this->roles,
            ])
            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'class' => Medewerker::class,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return BaseType::class;
    }
}
