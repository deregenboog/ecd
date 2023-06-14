<?php

namespace OekBundle\Form;

use AppBundle\Form\AppDateType;
use AppBundle\Form\BaseType;
use AppBundle\Form\MedewerkerType;
use Doctrine\ORM\EntityRepository;
use OekBundle\Entity\Afsluiting;
use OekBundle\Entity\VerwijzingNaar;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AfsluitingType extends AbstractType
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
                'class' => VerwijzingNaar::class,
                'query_builder' => function (EntityRepository $repository) {
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
            'data_class' => Afsluiting::class,
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
