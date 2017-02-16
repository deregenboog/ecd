<?php

namespace OekBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;
use AppBundle\Entity\Klant;
use AppBundle\Form\KlantType;
use OekBundle\Entity\OekKlant;
use AppBundle\Form\AppDateType;
use OekBundle\Entity\OekAanmelding;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use OekBundle\Entity\OekVerwijzingDoor;
use AppBundle\Form\MedewerkerType;

class OekAanmeldingType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('datum', AppDateType::class, ['data' => new \DateTime('today'), 'required' => true])
            ->add('verwijzing', null, [
                'label' => 'Verwijzing door',
                'placeholder' => 'Selecteer een item',
                'class' => OekVerwijzingDoor::class,
            ])
            ->add('medewerker', MedewerkerType::class)
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => OekAanmelding::class,
        ]);
    }
}
