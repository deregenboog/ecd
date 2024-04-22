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
use InloopBundle\Entity\Woonsituatie;
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
            'validation_groups'=>'toegang'
        ]);
    }



    public static function createToegang(FormBuilderInterface $builder, array $options)
    {
        $x = $builder
            ->create('toegang', null, [
                'compound' => true,
                'inherit_data' => true,
                'required' => true,

            ]);

        if (! ($builder->has('algemeen') && $builder->get('algemeen')->has('intakedatum'))) {
            $x->add('intakedatum',AppDateType::class,[
                'label'=>'Intakedatum eerste intake'
            ]);
        }
       $x
            ->add('verblijfsstatus', VerblijfsstatusSelectType::class, [
                'required' => true,
            ]);
        if (! ($builder->has('algemeen') && $builder->get('algemeen')->has('intakelocatie'))) {
            $x->
            add('intakelocatie', LocatieSelectType::class, [
                'required' => true,
                'placeholder' => '',
                'locatietypes' => ['Inloop'],
            ]);
        }
        $x
            ->add('toegangInloophuis', CheckboxType::class, [
                'required' => false,
            ])
            ->add('specifiekeLocaties', LocatieSelectType::class, [
                'required' => false,
                'multiple'=> true,
                'locatietypes' => ['Inloop'],
            ])
            ->add('overigenToegangVan', AppDateType::class, [
                'label' => 'Startdatum toegang overig',
                'required'=>false,
            ])
            ->add('gebruikersruimte', LocatieSelectType::class, [
                'required' => false,
                'gebruikersruimte' => true,
                'placeholder'=>'Kies een gebruikersruimte'

            ])
        ;
       return $x;

    }

}
