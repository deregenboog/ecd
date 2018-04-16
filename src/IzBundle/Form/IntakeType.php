<?php

namespace IzBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use AppBundle\Form\BaseType;
use AppBundle\Form\AppDateType;
use IzBundle\Entity\IzKlant;
use AppBundle\Form\MedewerkerType;
use IzBundle\Entity\IzVrijwilliger;
use IzBundle\Entity\Intake;
use AppBundle\Form\ZrmType;

class IntakeType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('intakedatum', AppDateType::class)
            ->add('medewerker', MedewerkerType::class)
            ->add('doelgroepen', null, [
                'expanded' => true,
            ])
        ;

        if (isset($options['data'])) {
            if ($options['data']->getIzDeelnemer() instanceof IzKlant) {
                $builder
                    ->add('gezinMetKinderen')
//                     ->add('zrm', ZrmType::class, [
//                         'required' => false,
//                         'data' => $options['data']->getZrm(),
//                     ])
                ;
            }

            if ($options['data']->getIzDeelnemer() instanceof IzVrijwilliger) {
                $builder->add('stagiair');
            }
        }

        $builder
            ->add('submit', SubmitType::class)
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'class' => Intake::class,
        ]);
    }

    public function getParent()
    {
        return BaseType::class;
    }
}
