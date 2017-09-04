<?php

namespace HsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use HsBundle\Entity\Registratie;
use AppBundle\Form\AppDateType;
use Doctrine\ORM\EntityRepository;
use AppBundle\Form\AppTimeType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use AppBundle\Form\MedewerkerType;
use AppBundle\Form\BaseType;
use HsBundle\Entity\Arbeider;
use HsBundle\Entity\Klus;

class RegistratieType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if ($options['data']->getKlus() && !$options['data']->getArbeider()) {
            $builder->add('arbeider', null, [
                'label' => 'Dienstverlener/vrijwilliger',
                'required' => true,
                'expanded' => true,
                'query_builder' => function (EntityRepository $repository) use ($options) {
                    return $repository->createQueryBuilder('arbeider')
                        ->where('arbeider IN (:dienstverleners) OR arbeider IN (:vrijwilligers)')
                        ->setParameters([
                            'dienstverleners' => $options['data']->getKlus()->getDienstverleners(),
                            'vrijwilligers' => $options['data']->getKlus()->getVrijwilligers(),
                        ])
                    ;
                },
            ]);
        } elseif ($options['data']->getArbeider() && !$options['data']->getKlus()) {
            $builder->add('klus', null, [
                'label' => 'Klus',
                'required' => true,
                'expanded' => true,
                'query_builder' => function (EntityRepository $repository) use ($options) {
                    return $repository->createQueryBuilder('klus')
                        ->where('klus IN (:klussen)')
                        ->setParameter('klussen', $options['data']->getArbeider()->getKlussen())
                    ;
                },
            ])
            ;
        }

        $builder
            ->add('medewerker', MedewerkerType::class)
            ->add('activiteit', null, [
                'placeholder' => 'Selecteer een activiteit',
            ])
            ->add('datum', AppDateType::class, ['data' => new \DateTime('today')])
            ->add('start', AppTimeType::class)
            ->add('eind', AppTimeType::class)
            ->add('reiskosten', MoneyType::class, ['required' => false])
        ;

        $builder->add('submit', SubmitType::class);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Registratie::class,
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
