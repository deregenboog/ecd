<?php

namespace IzBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use IzBundle\Entity\IzProject;
use AppBundle\Form\StadsdeelFilterType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use IzBundle\Filter\IzDeelnemerSelectie;
use Doctrine\ORM\EntityRepository;
use AppBundle\Form\FilterType;

class IzDeelnemerSelectieType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('izProjecten', EntityType::class, [
                'class' => IzProject::class,
                'required' => false,
                'multiple' => true,
                'label' => 'Projecten',
                'query_builder' => function (EntityRepository $repo) {
                    return $repo->createQueryBuilder('izProject')
                    ->where('izProject.einddatum IS NULL OR izProject.einddatum >= :now')
                    ->orderBy('izProject.naam', 'ASC')
                    ->setParameter('now', new \DateTime())
                ;
                },
                ])
            ->add('stadsdelen', StadsdeelFilterType::class, [
                'label' => 'Stadsdelen',
                'required' => true,
                'multiple' => true,
            ])
            ->add('personen', ChoiceType::class, [
                'label' => 'Personen',
                'required' => true,
                'multiple' => true,
                'expanded' => true,
                'choices' => [
                    'Deelnemers' => 'klanten',
                    'Vrijwilligers' => 'vrijwilligers',
                ],
                'data' => [
                    'klanten',
                    'vrijwilligers',
                ],
            ])
            ->add('communicatie', ChoiceType::class, [
                'label' => 'Communicatievorm',
                'required' => true,
                'multiple' => true,
                'expanded' => true,
                'choices' => [
                    'Geen email' => 'geen_email',
                    'Geen post' => 'geen_post',
                ],
            ])
            ->add('formaat', ChoiceType::class, [
                'label' => 'Actie',
                'required' => true,
                'expanded' => true,
                'choices' => [
                    'Excel-lijst' => 'excel',
                    'E-mail' => 'email',
                    'Etiketten' => 'etiketten',
                ],
                'data' => 'excel',
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => IzDeelnemerSelectie::class,
            'method' => 'GET',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return FilterType::class;
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return \AppBundle\Form\BaseType::class;
    }
}
