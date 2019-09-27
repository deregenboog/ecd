<?php

namespace GaBundle\Form;

use AppBundle\Form\FilterType;
use AppBundle\Form\StadsdeelSelectType;
use Doctrine\ORM\EntityRepository;
use GaBundle\Entity\Groep;
use GaBundle\Filter\SelectieFilter;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SelectieType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('alleGroepen', CheckboxType::class, [
                'mapped' => false,
                'required' => false,
            ])
            ->add('groepen', EntityType::class, [
                'class' => Groep::class,
                'required' => false,
                'multiple' => true,
                'expanded' => true,
                'label' => 'Groepen',
                'query_builder' => function (EntityRepository $repo) {
                    return $repo->createQueryBuilder('groep')
                        ->where('groep.einddatum IS NULL OR groep.einddatum >= :now')
                        ->orderBy('groep.naam', 'ASC')
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
                    'E-mail' => 'email',
                    'Post' => 'post',
                ],
            ])
            ->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
                $form = $event->getForm();
                $data = $event->getData();
                if (isset($data['alleGroepen'])) {
                    unset($data['groepen']);
                }
                if (isset($data['alleStadsdelen'])) {
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
            'data_class' => SelectieFilter::class,
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
