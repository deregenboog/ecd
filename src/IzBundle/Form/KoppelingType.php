<?php

namespace IzBundle\Form;

use AppBundle\Form\AppDateType;
use AppBundle\Form\BaseType;
use AppBundle\Form\DummyChoiceType;
use IzBundle\Entity\Hulpaanbod;
use IzBundle\Entity\Hulpvraag;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Form\MedewerkerType;

class KoppelingType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $hulpvraag = $options['data'];
        $hulpaanbod = $hulpvraag->getKoppeling()->getHulpaanbod();

        if ($hulpvraag instanceof Hulpvraag) {
            $builder->add('hulpvraag', HulpvraagType::class, [
                'inherit_data' => true,
            ]);
            foreach ($builder->get('hulpvraag')->all() as $elm) {
                $builder->get('hulpvraag')->remove($elm->getName());
            }
            $builder->get('hulpvraag')
                ->add('deelnemer', DummyChoiceType::class, [
                    'dummy_label' => (string) $hulpvraag,
                ])
                ->add('medewerker', MedewerkerType::class, [
                    'label' => 'Coördinator',
                ])
            ;
        }

        if ($hulpaanbod instanceof Hulpaanbod) {
            $builder->add('hulpaanbod', HulpaanbodType::class);
            foreach ($builder->get('hulpaanbod')->all() as $elm) {
                $builder->get('hulpaanbod')->remove($elm->getName());
            }
            $builder->get('hulpaanbod')
                ->add('vrijwilliger', DummyChoiceType::class, [
                    'dummy_label' => (string) $hulpaanbod,
                ])
                ->add('medewerker', MedewerkerType::class, [
                    'label' => 'Coördinator',
                ])
            ;
        }

        $builder
            ->add('koppelingStartdatum', AppDateType::class, [
                'label' => 'Startdatum koppeling',
                'required' => true,
            ])
            ->add('tussenevaluatiedatum', AppDateType::class, [
                'label' => 'Datum tussenevaluatie',
                'required' => true,
            ])
            ->add('eindevaluatiedatum', AppDateType::class, [
                'label' => 'Datum eindevaluatie',
                'required' => true,
            ])
            ->add('submit', SubmitType::class)
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Hulpvraag::class,
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
