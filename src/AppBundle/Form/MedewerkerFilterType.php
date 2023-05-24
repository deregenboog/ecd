<?php

namespace AppBundle\Form;

use AppBundle\Filter\MedewerkerFilter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MedewerkerFilterType extends AbstractType
{
    /**
     * @var array
     */
    private $roles;

    public function __construct(array $roleHierarchy)
    {
        $roles = array_keys($roleHierarchy);
        sort($roles);
        $this->roles = array_combine($roles, $roles);
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (in_array('username', $options['enabled_filters'])) {
            $builder->add('username', null, [
                'required' => false,
                'attr' => ['placeholder' => 'Username'],
            ]);
        }

        if (in_array('naam', $options['enabled_filters'])) {
            $builder->add('naam', null, [
                'required' => false,
                'attr' => ['placeholder' => 'Naam'],
            ]);
        }

        if (in_array('rol', $options['enabled_filters'])) {
            $builder->add('rol', ChoiceType::class, [
                'required' => false,
                'attr' => ['placeholder' => ''],
                'choices' => $this->roles,
            ]);
        }

        if (in_array('actief', $options['enabled_filters'])) {
            $builder->add('actief', JaNeeType::class, [
                'required' => false,
                'expanded' => false,
            ]);
        }

        $builder->add('filter', SubmitType::class);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => MedewerkerFilter::class,
            'data' => new MedewerkerFilter(),
            'enabled_filters' => [
                'username',
                'naam',
                'rol',
                'actief',
                'download'
            ],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getParent(): ?string
    {
        return FilterType::class;
    }
}
