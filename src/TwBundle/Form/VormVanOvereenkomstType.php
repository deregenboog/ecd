<?php

namespace TwBundle\Form;

use AppBundle\Form\AppDateType;
use AppBundle\Form\BaseType;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use TwBundle\Entity\VormVanOvereenkomst;

class VormVanOvereenkomstType extends AbstractType
{
    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    public function __construct(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('label')
            ->add('startdate', AppDateType::class)
            ->add('enddate', AppDateType::class, [
                'required' => false,
            ])
            ->add('submit', SubmitType::class, ['label' => 'Opslaan'])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => VormVanOvereenkomst::class,
        ]);
    }

    public function getParent(): ?string
    {
        return BaseType::class;
    }
}
