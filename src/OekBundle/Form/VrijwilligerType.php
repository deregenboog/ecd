<?php

namespace OekBundle\Form;

use AppBundle\Entity\Vrijwilliger as AppVrijwilliger;
use AppBundle\Form\AppDateType;
use AppBundle\Form\BaseType;
use AppBundle\Form\DummyChoiceType;
use AppBundle\Form\MedewerkerType;
use AppBundle\Form\VrijwilligerType as AppVrijwilligerType;
use OekBundle\Entity\Vrijwilliger;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VrijwilligerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /* @var $vrijwilliger Vrijwilliger */
        $vrijwilliger = $options['data'];

        if ($vrijwilliger instanceof Vrijwilliger
            && $vrijwilliger->getVrijwilliger() instanceof AppVrijwilliger
            && $vrijwilliger->getVrijwilliger()->getId()
        ) {
            $builder->add('vrijwilliger', DummyChoiceType::class, [
                'dummy_label' => (string) $vrijwilliger,
            ]);
        } else {
            $builder
                ->add('vrijwilliger', AppVrijwilligerType::class)
                ->get('vrijwilliger')
                ->remove('opmerking')
                ->remove('geenPost')
                ->remove('geenEmail')
            ;
        }

        $builder
            ->add('medewerker', MedewerkerType::class)
            ->add('actief', CheckboxType::class, [
                'required' => false,
            ])
            ->add('afsluitdatum', AppDateType::class, [
                'required' => false,
            ])
            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Vrijwilliger::class,
        ]);
    }

    public function getParent(): ?string
    {
        return BaseType::class;
    }
}
