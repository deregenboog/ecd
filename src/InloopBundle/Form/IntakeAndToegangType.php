<?php

namespace InloopBundle\Form;

use AppBundle\Entity\Inkomen;
use AppBundle\Form\AppDateType;
use AppBundle\Form\AppTextareaType;
use AppBundle\Form\BaseType;
use AppBundle\Form\JaNeeType;
use AppBundle\Form\MedewerkerType;
use AppBundle\Form\ZrmType;
use InloopBundle\Entity\Instantie;
use InloopBundle\Entity\Intake;
use InloopBundle\Entity\Toegang;
use InloopBundle\Entity\Woonsituatie;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IntakeAndToegangType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add($this->createAlgemeen($builder, $options))
            ->add($this->createAdresgegevens($builder, $options))
            ->add(ToegangType::createToegang($builder, $options))


            ->add($this->createLegitimatiebewijs($builder, $options))
            ->add($this->createVerslaving($builder, $options))
            ->add($this->createInkomen($builder, $options))
            ->add($this->createWoonsituatie($builder, $options))
            ->add($this->createOverigeHulpverlening($builder, $options))
            ->add($this->createVerwachtingenPlannen($builder, $options))
            ->add($this->createIndrukDoelgroep($builder, $options))
            ->add($this->createOndersteuning($builder, $options))
        ;


        if ($options['data']->getZrm()) {
            $builder->add('zrm', ZrmType::class, [
                'label' => 'ZRM',
                'data_class' => get_class($options['data']->getZrm()),
                'request_module' => 'Intake',
            ]);
//            $builder->get('zrm')->remove("medewerker"); // erg irritant dat ook hier een medewerker wordt laten zien. @TODO keertje weghalen? navragen
        } else {
            $builder->add('zrm', ZrmType::class, [
                'label' => 'ZRM',
                'request_module' => 'Intake',
            ]);
            $builder->get('zrm')->remove("medewerker");
        }

        $builder->add('submit', SubmitType::class);
    }

    /**
     * {@inheritdoc}
     */
    public function getParent(): ?string
    {
        return BaseType::class;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Intake::class,
        ]);
    }

    private function createAlgemeen(FormBuilderInterface $builder, array $options)
    {
        return $builder
            ->create('algemeen', null, [
                'compound' => true,
                'inherit_data' => true,
                'required'=>true,
            ])
            ->add('medewerker', MedewerkerType::class)
            ->add('intakedatum', AppDateType::class)
            ->add('geinformeerdOpslaanGegevens', CheckboxType::class,['required'=>true])
        ;

    }

    private function createAdresgegevens(FormBuilderInterface $builder, array $options)
    {
        return $builder
            ->create('adresgegevens', null, [
                'required' => true,
                'compound' => true,
                'inherit_data' => true,
            ])
            ->add('postadres', null, ['required' => false])
            ->add('postcode', null, ['required' => false])
            ->add('woonplaats', null, ['required' => false])
            ->add('telefoonnummer', null, ['required' => false])
            ->add('verblijfInNederlandSinds', AppDateType::class, [
                'label' => 'Verblijf in Nederland sinds',
                'required' => false,
            ])
            ->add('verblijfInAmsterdamSinds', AppDateType::class, [
                'label' => 'Verblijf in Amsterdam sinds',
                'required' => true,
            ])

        ;
    }


    private function createLegitimatiebewijs(FormBuilderInterface $builder, array $options)
    {
        return $builder
            ->create('legitimatiebewijs', null, [
                'compound' => true,
                'inherit_data' => true,
            ])
            ->add('legitimatie', LegitimatieSelectType::class)
            ->add('legitimatieNummer')
            ->add('legitimatieGeldigTot', AppDateType::class)
        ;
    }

    private function createVerslaving(FormBuilderInterface $builder, array $options)
    {
        $verslaving = $builder->create('verslaving', null, [
            'compound' => true,
            'inherit_data' => true,
        ]);

        if ($options['data']->getPrimaireProblematiek()) {
            $primair = $builder->create('primair', null, [
                'compound' => true,
                'inherit_data' => true,
            ]);
            $primair
                ->add('primaireProblematiek', VerslavingSelectType::class)
                ->add('primaireProblematiekFrequentie', FrequentieSelectType::class)
                ->add('primaireProblematiekPeriode', PeriodeSelectType::class)
                ->add('primaireProblematiekGebruikswijzen', GebruikswijzeSelectType::class)
            ;
            $verslaving->add($primair);

            $secundair = $builder->create('secundair', null, [
                'compound' => true,
                'inherit_data' => true,
            ]);
            $secundair
                ->add('verslavingen', VerslavingSelectType::class, [
                    'label' => 'Secundaire problematiek',
                    'expanded' => true,
                    'multiple' => true,
                ])
                ->add('verslavingOverig', null, [
                    'label' => 'Overige vormen van verslaving',
                ])
                ->add('frequentie', FrequentieSelectType::class)
                ->add('periode', PeriodeSelectType::class)
                ->add('gebruikswijzen', GebruikswijzeSelectType::class)
            ;
            $verslaving->add($secundair);

            $algemeen = $builder->create('algemeen', null, [
                'compound' => true,
                'inherit_data' => true,
            ]);
            $algemeen->add('eersteGebruik', AppDateType::class);
            $verslaving->add($algemeen);
        } else {
            $verslaving
                ->add('verslavingen', VerslavingSelectType::class, [
                    'expanded' => true,
                    'multiple' => true,
                ])
                ->add('verslavingOverig', null, [
                    'label' => 'Overige vormen van verslaving',
                ])
            ;
        }

        return $verslaving;
    }

    private function createInkomen(FormBuilderInterface $builder, array $options)
    {
        return $builder
            ->create('inkomen', null, [
                'required' => true,
                'compound' => true,
                'inherit_data' => true,
            ])
            ->add('inkomens', EntityType::class, [
                'class' => Inkomen::class,
                'expanded' => true,
                'multiple' => true,
                'required' => true,
            ])
            ->add('inkomenOverig', null, ['required' => false])

        ;
    }

    private function createWoonsituatie(FormBuilderInterface $builder, array $options)
    {
        return $builder
            ->create('woonsituaties', null, [
                'required' => true,
                'compound' => true,
                'inherit_data' => true,
            ])
            ->add('woonsituatie', EntityType::class, [
                'class' => Woonsituatie::class,
                'required' => true,
                'placeholder' => '',
            ])
            ;
    }

    private function createOverigeHulpverlening(FormBuilderInterface $builder, array $options)
    {
        return $builder
            ->create('overigeHulpverlening', null, [
                'compound' => true,
                'inherit_data' => true,
            ])
            ->add('instanties', EntityType::class, [
                'class' => Instantie::class,
                'expanded' => true,
                'multiple' => true,
            ])
            ->add('opmerkingAndereInstanties', AppTextareaType::class)
            ->add('medischeAchtergrond', AppTextareaType::class)
        ;
    }

    private function createVerwachtingenPlannen(FormBuilderInterface $builder, array $options)
    {
        return $builder
            ->create('verwachtingenPlannen', null, [
                'required' => true,
                'compound' => true,
                'inherit_data' => true,
            ])
            ->add('verwachtingDienstaanbod', AppTextareaType::class, ['required' => true])
            ->add('toekomstplannen', AppTextareaType::class, ['required' => true])
        ;
    }

    private function createIndrukDoelgroep(FormBuilderInterface $builder, array $options)
    {
        return $builder
            ->create('indrukDoelgroep', null, [
                'required' => true,
                'compound' => true,
                'inherit_data' => true,
            ])
            ->add('indruk', AppTextareaType::class, ['required' => false])
            ->add('doelgroep', JaNeeType::class, ['required' => true])
        ;
    }

    private function createOndersteuning(FormBuilderInterface $builder, array $options)
    {
        return $builder
            ->create('ondersteuning', null, [
                'compound' => true,
                'inherit_data' => true,
            ])
            ->add('informeleZorg', JaNeeType::class, [
                'label' => 'Zou je het leuk vinden om iedere week met iemand samen iets te ondernemen?',
            ])
            ->add('dagbesteding', JaNeeType::class, [
                'label' => 'Zou je het leuk vinden om overdag iets te doen te hebben?',
            ])
            ->add('inloophuis', JaNeeType::class, [
                'label' => 'Zou je een plek in de buurt willen hebben waar je iedere dag koffie kan drinken en mensen kan ontmoeten?',
            ])
            ->add('hulpverlening', JaNeeType::class, [
                'label' => 'Heeft u hulp nodig met regelzaken?',
            ])
        ;
    }
}
