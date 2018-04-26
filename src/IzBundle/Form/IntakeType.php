<?php

namespace IzBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use AppBundle\Form\BaseType;
use AppBundle\Form\AppDateType;
use IzBundle\Entity\IzKlant;
use AppBundle\Form\MedewerkerType;
use IzBundle\Entity\IzVrijwilliger;
use IzBundle\Entity\Intake;
use AppBundle\Form\ZrmType;
use AppBundle\Form\AppTextareaType;
use AppBundle\Entity\Zrm;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class IntakeType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var $intake Intake */
        $intake = $options['data'];

        $builder->addViewTransformer(new CallbackTransformer(
            function(Intake $intake) {
                return new IntakeModel($intake);
            },
            function(IntakeModel $model) {
                return $model->getIntake();
            }
        ));

        $builder
            ->add('intakedatum', AppDateType::class)
            ->add('medewerker', MedewerkerType::class)
        ;

        if (!$intake->getId()) {
            $builder->add('verslag', AppTextareaType::class);
        }

        if ($intake) {
            if ($intake->getIzDeelnemer() instanceof IzKlant) {
                $builder->add('gezinMetKinderen', CheckboxType::class, [
                    'required' => false,
                ]);
                if (!$intake->getId()) {
                    $builder->add('zrm', ZrmType::class, [
                        'data' => Zrm::create($intake->getIntakeDatum(), 'IzIntake'),
                        'by_reference' => false,
                    ]);
                }
            } elseif ($intake->getIzDeelnemer() instanceof IzVrijwilliger) {
                $builder->add('stagiair', CheckboxType::class, [
                    'required' => false,
                ]);
            }
        }

        $builder->add('submit', SubmitType::class);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => IntakeModel::class,
        ]);
    }

    public function getParent()
    {
        return BaseType::class;
    }
}
