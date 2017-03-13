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

class RegistratieType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('vrijwilliger', null, [
                'placeholder' => 'Selecteer een vrijwilliger',
                'query_builder' => function (EntityRepository $repository) use ($options) {
                    return $repository->createQueryBuilder('vrijwilliger')
                        ->innerJoin('vrijwilliger.klussen', 'klus')
                        ->where('klus = :klus')
                        ->setParameter('klus', $options['data']->getKlus());
                },
            ])
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
}
