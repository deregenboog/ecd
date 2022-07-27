<?php

namespace OekraineBundle\Form;

use AppBundle\Entity\Inkomen;
use AppBundle\Form\AppDateType;
use AppBundle\Form\AppTextareaType;
use AppBundle\Form\BaseType;
use AppBundle\Form\JaNeeType;
use AppBundle\Form\MedewerkerType;
use AppBundle\Form\ZrmType;
use OekraineBundle\Entity\Instantie;
use OekraineBundle\Entity\Intake;
use OekraineBundle\Entity\Woonsituatie;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ToegangType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(self::createToegang($builder, $options))
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
            'validation_groups'=>'toegang'
        ]);
    }



    public static function createToegang(FormBuilderInterface $builder, array $options)
    {
        return $builder
            ->create('toegang', null, [
                'compound' => true,
                'inherit_data' => true,
                'required'=>true,

            ])
            ->add('verblijfsstatus', VerblijfsstatusSelectType::class, [
                'required' => true,
            ])
            ->add('intakelocatie', LocatieSelectType::class, [
                'required' => true,
            ])
            ->add('toegangInloophuis', CheckboxType::class, [
                'required' => false,
            ])
            ->add('amocToegangTot', AppDateType::class, [
                'label' => 'Einddatum toegang AMOC',
                'required'=>false,
            ])
            ->add('ondroBongToegangVan', AppDateType::class, [
                'label' => 'Startdatum toegang Zeeburg/Transformatorweg',
                'required'=>false,
            ])
            ->add('overigenToegangVan', AppDateType::class, [
                'label' => 'Startdatum toegang overigen',
                'required'=>false,
            ])
            ->add('gebruikersruimte', LocatieSelectType::class, [
                'required' => false,
                'gebruikersruimte' => true,
            ])
        ;
    }

}
