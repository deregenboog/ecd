<?php

namespace GaBundle\Form;

use AppBundle\Form\BaseType;
use GaBundle\Entity\Activiteit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DeelnamesType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /* @var $activiteit Activiteit */
        $activiteit = $options['data'];

        $builder
            ->add('deelnames', CollectionType::class, [
                'entry_type' => DeelnameType::class,
                'entry_options' => [
                    'embedded' => true,
                ],
            ])
            ->add('aantalAnoniemeDeelnemers', IntegerType::class)
            ->add('submit', SubmitType::class, ['label' => 'Opslaan'])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Activiteit::class,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getParent(): ?string
    {
        return BaseType::class;
    }
}
