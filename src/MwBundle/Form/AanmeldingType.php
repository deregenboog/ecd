<?php

namespace MwBundle\Form;

use AppBundle\Entity\Medewerker;
use AppBundle\Form\AppDateType;
use AppBundle\Form\BaseSelectType;
use AppBundle\Form\BaseType;
use InloopBundle\Form\LocatieSelectType;
use MwBundle\Entity\Aanmelding;
use MwBundle\Entity\Project;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AanmeldingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $openMet = $options['open_via'] ?? null;
        $medewerker = $options['data'] instanceof Aanmelding ? $options['data']->getMedeWerker() : null;

        $builder
            ->add('datum', AppDateType::class)
            ->add('locatie', LocatieSelectType::class, [
                'locatietypes' => 'Maatschappelijk Werk',
                'required' => true,
            ])
            ->add('project', BaseSelectType::class, [
                'class' => Project::class,
                'disabled' => BaseType::MODE_ADD != $options['mode'],
                'required' => true,
            ])
            ->add('binnenViaOptieKlant', BinnenViaOptieKlantSelectType::class)
        ;

        /// Dit wordt gebruikt wanneer een andere verantwoordelijke dit dossier aan het openen is
        if($openMet){
            $builder->add('open_via', \Symfony\Component\Form\Extension\Core\Type\ChoiceType::class, [
                'label' => 'Nieuwe verantwoordelijke medewerker',
                'choices' => [
                    'Te openen door mij ('.$medewerker->getNaam().')' => 'huidige_beheerder',
                    'Te openen door de vorige verantwoordelijke ('.$openMet->getNaam().')' => 'vorige_beheerder',
                ],
                'expanded' => true,
                'multiple' => false,
                'required' => true,
                'mapped' => false,
            ]);
        }

        $builder->add('submit', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Aanmelding::class,
            'class' => Aanmelding::class,
            'mode' => BaseType::MODE_ADD,
            'allow_extra_fields' => true,
            'open_via' => null,
        ]);
    }

    public function getParent(): ?string
    {
        return BaseType::class;
    }
}
