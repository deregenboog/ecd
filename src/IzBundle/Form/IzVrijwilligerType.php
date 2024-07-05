<?php

namespace IzBundle\Form;

use AppBundle\Entity\Vrijwilliger;
use AppBundle\Form\AppDateType;
use AppBundle\Form\AppTextareaType;
use AppBundle\Form\BaseType;
use AppBundle\Form\DummyChoiceType;
use AppBundle\Form\MedewerkerType;
use AppBundle\Form\VrijwilligerType;
use IzBundle\Entity\IzVrijwilliger;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IzVrijwilligerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if ($options['data'] instanceof IzVrijwilliger
            && $options['data']->getVrijwilliger() instanceof Vrijwilliger
            && $options['data']->getVrijwilliger()->getId()
        ) {
            $builder->add('vrijwilliger', DummyChoiceType::class, [
                'dummy_label' => (string) $options['data'],
            ]);
        } else {
            $builder->add('vrijwilliger', VrijwilligerType::class, ['required' => true]);
        }

        $builder
            ->add('datumAanmelding', AppDateType::class)
            ->add('medewerker', MedewerkerType::class, ['required' => true])
            ->add('binnengekomenVia', BinnengekomenViaSelectType::class, [
                'required' => false,
                'current' => $options['data'] ? $options['data']->getBinnengekomenVia() : null,
            ])
            ->add('notitie', AppTextareaType::class, [
                'required' => false,
            ])
            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'class' => IzVrijwilliger::class,
        ]);
    }

    public function getParent(): ?string
    {
        return BaseType::class;
    }
}
