<?php

namespace OekBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;
use AppBundle\Entity\Klant;
use OekBundle\Entity\OekKlant;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use AppBundle\Filter\FilterInterface;

class OekKlantSelectType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('form', HiddenType::class, ['mapped' => false, 'data' => self::class])
            ->add('klant', null, [
                'required' => false,
                'query_builder' => function (EntityRepository $repository) use ($options) {
                    $builder = $repository->createQueryBuilder('klant');

                    if ($options['filter'] instanceof FilterInterface) {
                        $options['filter']->applyTo($builder);
                    }

                    $builder->leftJoin(OekKlant::class, 'oekKlant', 'WITH', 'oekKlant.klant = klant')
                        ->andWhere('oekKlant.id IS NULL');

                    return $builder;
                },
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => OekKlant::class,
            'filter' => null,
        ]);
    }
}
