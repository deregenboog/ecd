<?php

namespace IzBundle\Form;

use AppBundle\Form\AppDateType;
use AppBundle\Form\BaseType;
use IzBundle\Entity\Afsluiting;
use IzBundle\Entity\IzDeelnemer;
use IzBundle\Entity\IzKlant;
use IzBundle\Entity\IzVrijwilliger;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IzDeelnemerCloseType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if ($options['data'] instanceof IzKlant) {
            $doelgroepen = Afsluiting::DOELGROEP_KLANT;
        } elseif ($options['data'] instanceof IzVrijwilliger) {
            $doelgroepen = Afsluiting::DOELGROEP_VRIJWILLIGER;
        } else {
            $doelgroepen = 0;
        }

        $builder
            ->add('afsluitdatum', AppDateType::class, [
                'label' => 'Afsluitdatum',
                'required' => true,
                'data' => new \DateTime('today'),
            ])
            ->add('afsluiting', AfsluitingSelectType::class, [
                'required' => true,
                'doelgroepen' => $doelgroepen,
            ])
            ->add('submit', SubmitType::class)
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'class' => IzDeelnemer::class,
        ]);
    }

    public function getParent(): ?string
    {
        return BaseType::class;
    }
}
