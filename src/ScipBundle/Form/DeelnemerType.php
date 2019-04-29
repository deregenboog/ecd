<?php

namespace ScipBundle\Form;

use AppBundle\Form\BaseType;
use AppBundle\Form\DummyChoiceType;
use AppBundle\Form\KlantType;
use ScipBundle\Entity\Deelnemer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class DeelnemerType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /* @var $deelnemer Deelnemer */
        $deelnemer = $options['data'];

        if ($deelnemer->getKlant() && $deelnemer->getKlant()->getId()) {
            $builder->add('klant', DummyChoiceType::class, [
                'label' => 'Vrijwilliger',
                'dummy_label' => (string) $deelnemer->getKlant(),
            ]);
        } else {
            $builder->add('klant', KlantType::class, [
                'label' => 'Vrijwilliger',
                'required' => true,
            ]);
        }

        $builder
            ->add('functie', null, [
                'required' => false,
            ])
            ->add('werkbegeleider', null, [
                'required' => false,
            ])
            ->add('type', ChoiceType::class, [
                'required' => false,
                'choices' => [
                    Deelnemer::TYPE_WMO => Deelnemer::TYPE_WMO,
                    Deelnemer::TYPE_ONDERAANNEMER => Deelnemer::TYPE_ONDERAANNEMER,
                ],
            ])
            ->add('risNummer', null, [
                'required' => false,
                'label' => 'RIS-nummer',
            ])
            ->add('labels', LabelSelectType::class, [
                'required' => false,
                'expanded' => true,
                'multiple' => true,
            ])
            ->add('submit', SubmitType::class)
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return BaseType::class;
    }
}
