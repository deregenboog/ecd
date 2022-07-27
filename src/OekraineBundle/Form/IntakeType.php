<?php

namespace OekraineBundle\Form;


use AppBundle\Form\AppDateType;
use AppBundle\Form\AppTextareaType;
use AppBundle\Form\BaseType;
use AppBundle\Form\JaNeeType;
use AppBundle\Form\MedewerkerType;
use AppBundle\Form\ZrmType;
use InloopBundle\Form\LegitimatieSelectType;
use OekraineBundle\Entity\Instantie;
use OekraineBundle\Entity\Intake;
use OekraineBundle\Entity\Inkomen;
use OekraineBundle\Entity\Woonsituatie;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IntakeType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add($this->createAlgemeen($builder, $options))
            ->add($this->createAdresgegevens($builder, $options))
            ->add($this->createLegitimatiebewijs($builder, $options))

            ->add($this->createInkomen($builder, $options))
            ->add($this->createOverigeHulpverlening($builder, $options))
            ->add($this->createVerwachtingenPlannen($builder, $options))
            ->add($this->createIndrukDoelgroep($builder, $options))
            ->add($this->createOndersteuning($builder, $options))
        ;


        $builder->add('submit', SubmitType::class);
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
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
            ->add('intakelocatie', LocatieSelectType::class)
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
            ->add('woonlocatie', LocatieSelectType::class)
            ->add('kamernummer')
//            ->add('postadres', null, ['required' => false])
//            ->add('postcode', null, ['required' => false])
//            ->add('woonplaats', null, ['required' => false])
//            ->add('telefoonnummer', null, ['required' => false])
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


    private function createOverigeHulpverlening(FormBuilderInterface $builder, array $options)
    {
        return $builder
            ->create('overigeHulpverlening', null, [
                'compound' => true,
                'inherit_data' => true,
            ])
//            ->add('instanties', EntityType::class, [
//                'class' => \InloopBundle\Entity\Instantie::class,
//                'expanded' => true,
//                'multiple' => true,
//            ])
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
//            ->add('verwachtingDienstaanbod', AppTextareaType::class, ['required' => true])
            ->add('toekomstplannen', AppTextareaType::class, ['required' => false])
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
            ->add('werkhulp', JaNeeType::class, [
                'label' => 'Wil je hulp bij het zoeken naar werken?',
            ])
            ->add('hulpverlening', JaNeeType::class, [
                'label' => 'Heeft u hulp nodig met regelzaken?',
            ])
        ;
    }
}
