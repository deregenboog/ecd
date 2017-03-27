<?php

namespace OekBundle\Form;

use Doctrine\ORM\EntityRepository;
use AppBundle\Form\BaseType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Form\AppDateType;
use OekBundle\Entity\OekVerwijzingNaar;
use OekBundle\Entity\OekAfsluiting;
use AppBundle\Form\MedewerkerType;

class OekAfsluitingType extends BaseType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('datum', AppDateType::class, ['data' => new \DateTime('today'), 'required' => true])
            ->add('verwijzing', null, [
                'label' => 'Verwijzing naar',
                'placeholder' => 'Selecteer een item',
                'class' => OekVerwijzingNaar::class,
                'query_builder' => function(EntityRepository $repository) {
                    return $repository
                        ->createQueryBuilder('verwijzing')
                        ->where('verwijzing.actief = 1');
                },
            ])
            ->add('medewerker', MedewerkerType::class)
            ->add('submit', SubmitType::class, ['label' => 'Opslaan'])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => OekAfsluiting::class,
        ]);
    }
}
