<?php

namespace HsBundle\Form;

use AppBundle\Form\BaseType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use HsBundle\Entity\HsRegistratie;
use AppBundle\Form\AppDateType;
use Doctrine\ORM\EntityRepository;
use AppBundle\Form\AppTimeType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;

class HsRegistratieType extends BaseType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('hsVrijwilliger', null, [
                'label' => 'Vrijwilliger',
                'placeholder' => 'Selecteer een vrijwilliger',
                'query_builder' => function (EntityRepository $repository) use ($options) {
                    return $repository->createQueryBuilder('hsVrijwilliger')
                        ->innerJoin('hsVrijwilliger.hsKlussen', 'hsKlus')
                        ->where('hsKlus = :hs_klus')
                        ->setParameter('hs_klus', $options['data']->getHsKlus());
                },
            ])
            ->add('medewerker')
            ->add('hsActiviteit', null, [
                'label' => 'Activiteit',
                'placeholder' => 'Selecteer een activiteit',
            ])
            ->add('datum', AppDateType::class, ['data' => new \DateTime('today')])
            ->add('start', AppTimeType::class)
            ->add('eind', AppTimeType::class)
            ->add('reiskosten', MoneyType::class, ['required' => false])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => HsRegistratie::class,
        ]);
    }
}
