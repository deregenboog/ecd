<?php

namespace GaBundle\Form;

use AppBundle\Form\AppDateType;
use AppBundle\Form\BaseType;
use AppBundle\Form\DummyChoiceType;
use GaBundle\Entity\Activiteit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ActiviteitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $activiteit = $options['data'];

        if ($this->hasGroep($activiteit)) {
            $builder->add('groep', DummyChoiceType::class, [
                'dummy_label' => (string) $activiteit->getGroep(),
            ]);
        } else {
            $builder->add('groep', GroepSelectType::class, ['required' => false]);
        }

        $builder
            ->add('naam')
            ->add('datum', AppDateType::class)
            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Activiteit::class,
        ]);
    }

    public function getParent(): ?string
    {
        return BaseType::class;
    }

    private function hasGroep(Activiteit $activiteit)
    {
        return $activiteit && $activiteit->getGroep() && $activiteit->getGroep()->getId();
    }
}
