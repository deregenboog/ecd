<?php

namespace OdpBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;
use AppBundle\Entity\Klant;
use AppBundle\Filter\FilterInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use OdpBundle\Entity\Huurder;

class HuurderSelectType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('klant', null, [
                'required' => false,
                'query_builder' => function (EntityRepository $repository) use ($options) {
                    $builder = $repository->createQueryBuilder('klant')
                        ->leftJoin(Huurder::class, 'huurder', 'WITH', 'huurder.klant = klant')
                        ->andWhere('huurder.id IS NULL')
                        ->orderBy('klant.achternaam, klant.tussenvoegsel, klant.voornaam')
                    ;

                    if ($options['filter'] instanceof FilterInterface) {
                        $options['filter']->applyTo($builder);
                    }

                    return $builder;
                },
            ])
            ->add('submit', SubmitType::class, ['label' => 'Verder'])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Huurder::class,
            'filter' => null,
        ]);
    }
}
