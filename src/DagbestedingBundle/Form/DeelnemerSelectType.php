<?php

namespace DagbestedingBundle\Form;

use AppBundle\Filter\FilterInterface;
use AppBundle\Form\BaseType;
use DagbestedingBundle\Entity\Deelnemer;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DeelnemerSelectType extends AbstractType
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
                        ->leftJoin(Deelnemer::class, 'deelnemer', 'WITH', 'deelnemer.klant = klant')
                        ->andWhere('deelnemer.id IS NULL')
                        ->orderBy('klant.achternaam, klant.tussenvoegsel, klant.voornaam')
                    ;

                    if ($options['filter'] instanceof FilterInterface) {
                        $options['filter']->applyTo($builder);
                    }

                    return $builder;
                },
            ])
            ->add('next', SubmitType::class, ['label' => 'Verder'])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Deelnemer::class,
            'filter' => null,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getParent(): ?string
    {
        return BaseType::class;
    }
}
