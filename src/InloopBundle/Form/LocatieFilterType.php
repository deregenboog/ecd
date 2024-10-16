<?php

namespace InloopBundle\Form;

use AppBundle\Form\FilterType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use InloopBundle\Filter\LocatieFilter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LocatieFilterType extends AbstractType
{
    /** @var EntityRepository */
    protected $locatieTypeRepo;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->locatieTypeRepo = $entityManager->getRepository(\InloopBundle\Entity\LocatieType::class);
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $defaultLocatieType = $this->locatieTypeRepo->findBy(['naam' => 'Inloop']);
        if (in_array('naam', $options['enabled_filters'])) {
            $builder->add('naam');
        }

        if (in_array('locatieTypes', $options['enabled_filters'])) {
            $builder->add('locatieTypes', LocatieTypeSelectType::class, [
                'multiple' => true,
                'required' => false,
                'data' => $defaultLocatieType,
            ]);
        }

        if (in_array('status', $options['enabled_filters'])) {
            $builder->add('status', ChoiceType::class, [
                'required' => false,
                'choices' => [
                    'Actieve locaties' => LocatieFilter::STATUS_ACTIEF,
                    'Inactieve locaties' => LocatieFilter::STATUS_INACTIEF,
                ],
            ]);
        }

        $builder
            ->add('filter', SubmitType::class, ['label' => 'Filteren'])
//             ->add('download', SubmitType::class, ['label' => 'Downloaden'])
        ;

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($defaultLocatieType) {
            $data = $event->getData();
            $data->locatieTypes = $defaultLocatieType;
        });
    }

    public function getParent(): ?string
    {
        return FilterType::class;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => LocatieFilter::class,
            'data' => new LocatieFilter(),
            'enabled_filters' => [
                'naam',
                'locatieTypes',
                'status',
            ],
        ]);
    }
}
