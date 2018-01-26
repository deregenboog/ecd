<?php

namespace GaBundle\Form;

use AppBundle\Exception\AppException;
use AppBundle\Form\BaseType;
use AppBundle\Form\DummyChoiceType;
use GaBundle\Entity\Deelname;
use GaBundle\Entity\Klantdossier;
use GaBundle\Entity\Vrijwilligerdossier;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DeelnameType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if ($options['embedded']) {
            $builder->add('status', ChoiceType::class, [
                'label' => false,
                'required' => true,
                'expanded' => true,
                'choices' => [
                    Deelname::STATUS_AANWEZIG => Deelname::STATUS_AANWEZIG,
                    Deelname::STATUS_AFWEZIG => Deelname::STATUS_AFWEZIG,
                ],
            ]);

            return;
        }

        /** @var $deelname Deelname */
        $deelname = $options['data'];

        if ($deelname->getDossier()) {
            $builder->add('dossier', DummyChoiceType::class, [
                'dummy_label' => (string) $deelname->getDossier(),
            ]);
        } else {
            $builder->add('dossier', $this->getDossierSelectType($options), [
                'activiteit' => $deelname->getActiviteit(),
            ]);
        }

        if ($deelname->getActiviteit()) {
            $builder->add('activiteit', DummyChoiceType::class, [
                'dummy_label' => (string) $deelname->getActiviteit(),
            ]);
        } else {
            $builder->add('activiteit', ActiviteitSelectType::class, [
                'dossier' => $deelname->getDossier(),
            ]);
        }

        if ($deelname->getDossier() && $deelname->getActiviteit()) {
            $builder->add('status', ChoiceType::class, [
                'required' => true,
                'expanded' => true,
                'choices' => [
                    Deelname::STATUS_AANWEZIG => Deelname::STATUS_AANWEZIG,
                    Deelname::STATUS_AFWEZIG => Deelname::STATUS_AFWEZIG,
                ],
            ]);
        }

        $builder->add('submit', SubmitType::class, ['label' => 'Opslaan']);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Deelname::class,
            'dossier_class' => null,
            'embedded' => false,
        ]);

        $resolver->setAllowedValues('dossier_class', [
            null,
            Klantdossier::class,
            Vrijwilligerdossier::class,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return BaseType::class;
    }

    private function getDossierSelectType(array $options)
    {
        if (!array_key_exists('dossier_class', $options)) {
            throw new AppException('Options "dossier_class" must be set.');
        }

        switch ($options['dossier_class']) {
            case Klantdossier::class:
                return KlantdossierSelectType::class;
            case Vrijwilligerdossier::class:
                return VrijwilligerdossierSelectType::class;
            default:
                throw new AppException(sprintf(
                    'Value "%s" of option "dossier_class" is invalid.',
                    $options['dossier_class']
                ));
        }
    }
}
