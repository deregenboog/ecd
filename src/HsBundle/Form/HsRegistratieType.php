<?php

namespace HsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use HsBundle\Entity\HsRegistratie;
use AppBundle\Form\AppDateType;
use Doctrine\ORM\EntityRepository;

class HsRegistratieType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('medewerker')
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
            ->add('hsActiviteit', null, [
                'label' => 'Activiteit',
                'placeholder' => 'Selecteer een activiteit',
            ])
            ->add('datum', AppDateType::class, ['data' => new \DateTime('today')])
            ->add('start')
            ->add('eind')
            ->add('reiskosten')
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
