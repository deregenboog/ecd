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
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;

class IzDeelnemerSelectieType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('alleProjecten', CheckboxType::class, [
                'mapped' => false,
                'required' => false,
            ])
            ->add('izProjecten', EntityType::class, [
                'class' => IzProject::class,
                'required' => false,
                'multiple' => true,
                'expanded' => true,
                'label' => 'Projecten',
                'query_builder' => function (EntityRepository $repo) {
                    return $repo->createQueryBuilder('izProject')
                    ->where('izProject.einddatum IS NULL OR izProject.einddatum >= :now')
                    ->orderBy('izProject.naam', 'ASC')
                    ->setParameter('now', new \DateTime())
                ;
                },
            ])
            ->add('alleStadsdelen', CheckboxType::class, [
                'mapped' => false,
                'required' => false,
            ])
            ->add('stadsdelen', StadsdeelFilterType::class, [
                'label' => 'Stadsdelen',
                'required' => false,
                'multiple' => true,
                'expanded' => true,
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
                ],
                'data' => 'excel',
            ])
            ->remove('filter')
            ->remove('download')
            ->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
                $data = $event->getData();
                if ($data['alleProjecten']) {
                    unset($data['izProjecten']);
                }
                if ($data['alleStadsdelen']) {
                    unset($data['stadsdelen']);
                }
                $event->setData($data);
            })
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
}
