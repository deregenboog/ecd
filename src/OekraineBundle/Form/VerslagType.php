<?php

namespace OekraineBundle\Form;

use AppBundle\Form\AppDateType;
use AppBundle\Form\BaseType;
use AppBundle\Form\MedewerkerType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use OekraineBundle\Entity\Verslag;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VerslagType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $accessChoices = [
            Verslag::$accessTypes[Verslag::ACCESS_PSYCH] => Verslag::ACCESS_PSYCH,
            Verslag::$accessTypes[Verslag::ACCESS_MW] => Verslag::ACCESS_MW,
            Verslag::$accessTypes[Verslag::ACCESS_INLOOP] => Verslag::ACCESS_INLOOP,
        ];

        // only access options that you are allowed to, should be selectable.
        $choiceAttr = [];
        foreach ($accessChoices as $k => $v) {
            if ($v > $options['accessProfile']) {
                $choiceAttr[$k] = ['disabled' => 'disabled'];
            }
        }
        $builder
            ->add('medewerker', MedewerkerType::class)
            ->add('datum', AppDateType::class, [
                'required' => true,
            ])
            ->add('locatie', LocatieSelectType::class)
            ->add('aantalContactmomenten', IntegerType::class)
            ->add('opmerking', CKEditorType::class, ['attr' => ['rows' => 10, 'cols' => 50], 'required' => true])
            ->add('access', ChoiceType::class, [
                'required' => true,
                'label' => 'Zichtbaar voor',
                'choices' => $accessChoices,
                'choice_attr' => $choiceAttr,
                ])
            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Verslag::class,
//            'attr' => ['novalidate' => 'novalidate'],
            'inventarisaties' => [],
            'accessProfile' => [],
        ]);
    }

    public function getParent(): ?string
    {
        return BaseType::class;
    }
}
