<?php

namespace GaBundle\Form;

use AppBundle\Exception\AppException;
use AppBundle\Form\AppDateType;
use AppBundle\Form\BaseType;
use AppBundle\Form\DummyChoiceType;
use GaBundle\Entity\Klantdossier;
use GaBundle\Entity\Lidmaatschap;
use GaBundle\Entity\Vrijwilligerdossier;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LidmaatschapType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /* @var $lidmaatschap Lidmaatschap */
        $lidmaatschap = $options['data'];

        if ($lidmaatschap->getDossier()) {
            $builder->add('dossier', DummyChoiceType::class, [
                'dummy_label' => (string) $lidmaatschap->getDossier(),
            ]);
        } else {
            $builder->add('dossier', $this->getDossierSelectType($options), [
                'groep' => $lidmaatschap->getGroep(),
            ]);
        }

        if ($lidmaatschap->getGroep()) {
            $builder->add('groep', DummyChoiceType::class, [
                'dummy_label' => (string) $lidmaatschap->getGroep(),
            ]);
        } else {
            $builder->add('groep', GroepSelectType::class, [
                'dossier' => $lidmaatschap->getDossier(),
            ]);
        }

        $builder->add('startdatum', AppDateType::class)
            ->add('startdatum', AppDateType::class)
            ->add('communicatieEmail')
            ->add('communicatiePost')
            ->add('communicatieTelefoon')
            ->add('submit', SubmitType::class, ['label' => 'Opslaan'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Lidmaatschap::class,
            'dossier_class' => null,
        ]);

        $resolver->setAllowedValues('dossier_class', [
            null,
            Klantdossier::class,
            Vrijwilligerdossier::class,
        ]);
    }

    public function getParent(): ?string
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
                throw new AppException(sprintf('Value "%s" of option "dossier_class" is invalid.', $options['dossier_class']));
        }
    }
}
