<?php

namespace IzBundle\Form;

use AppBundle\Form\FilterType;
use AppBundle\Form\StadsdeelSelectType;
use Doctrine\ORM\EntityRepository;
use IzBundle\Entity\Project;
use IzBundle\Filter\IzDeelnemerSelectie;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

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
            ->add('projecten', EntityType::class, [
                'class' => Project::class,
                'required' => false,
                'multiple' => true,
                'expanded' => true,
                'label' => 'Projecten',
                'query_builder' => function (EntityRepository $repo) {
                    return $repo->createQueryBuilder('project')
                        ->where('project.einddatum IS NULL OR project.einddatum >= :now')
                        ->orderBy('project.naam', 'ASC')
                        ->setParameter('now', new \DateTime())
                    ;
                },
            ])
            ->add('alleStadsdelen', CheckboxType::class, [
                'mapped' => false,
                'required' => false,
            ])
            ->add('stadsdelen', StadsdeelSelectType::class, [
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
                    'E-mail' => 'geen_email',
                    'Post' => 'geen_post',
                ],
            ])
            ->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
                $data = $event->getData();
                if ($data['alleProjecten']) {
                    unset($data['projecten']);
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
