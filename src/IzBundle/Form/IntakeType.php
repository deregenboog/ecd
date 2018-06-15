<?php

namespace IzBundle\Form;

use AppBundle\Form\AppDateType;
use AppBundle\Form\BaseType;
use AppBundle\Form\MedewerkerType;
use AppBundle\Form\ZrmType;
use IzBundle\Entity\Intake;
use IzBundle\Entity\IzKlant;
use IzBundle\Entity\IzVrijwilliger;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

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
