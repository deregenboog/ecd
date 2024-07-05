<?php

namespace OekraineBundle\Form;

use AppBundle\Form\AppDateType;
use AppBundle\Form\BaseType;
use OekraineBundle\Entity\Intake;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ToegangType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(self::createToegang($builder, $options))
        ;
        $builder->add('submit', SubmitType::class);
    }

    public function getParent(): ?string
    {
        return BaseType::class;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Intake::class,
            'validation_groups' => 'toegang',
        ]);
    }

    public static function createToegang(FormBuilderInterface $builder, array $options)
    {
        return $builder
            ->create('toegang', null, [
                'compound' => true,
                'inherit_data' => true,
                'required' => true,
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
                'required' => false,
            ])
            ->add('ondroBongToegangVan', AppDateType::class, [
                'label' => 'Startdatum toegang Zeeburg/Transformatorweg',
                'required' => false,
            ])
            ->add('overigenToegangVan', AppDateType::class, [
                'label' => 'Startdatum toegang overigen',
                'required' => false,
            ])
            ->add('gebruikersruimte', LocatieSelectType::class, [
                'required' => false,
                'gebruikersruimte' => true,
            ])
        ;
    }
}
