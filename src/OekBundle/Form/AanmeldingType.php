<?php

namespace OekBundle\Form;

use AppBundle\Form\AppDateType;
use AppBundle\Form\BaseType;
use AppBundle\Form\MedewerkerType;
use Doctrine\ORM\EntityRepository;
use OekBundle\Entity\Aanmelding;
use OekBundle\Entity\VerwijzingDoor;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AanmeldingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('datum', AppDateType::class, ['data' => new \DateTime('today'), 'required' => true])
            ->add('verwijzing', null, [
                'label' => 'Verwijzing door',
                'placeholder' => 'Selecteer een item',
                'class' => VerwijzingDoor::class,
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

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Aanmelding::class,
        ]);
    }

    public function getParent(): ?string
    {
        return BaseType::class;
    }
}
