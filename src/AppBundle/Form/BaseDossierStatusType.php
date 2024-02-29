<?php

namespace AppBundle\Form;

use AppBundle\Model\HasDossierStatusInterface;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use VillaBundle\Entity\Aanmelding;

/**
 * A form type which is primarily for entities which implement HasDossierStatusInterface.
 * Those entities, like Bezoeker, Deelnemer, Slaper, have a klantRelation, but also a DossierStatus. This form type supplies
 * an eventListener to add the dossierStatus form to the form, in case of a creation.
 */
class BaseDossierStatusType extends BaseType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->addDossierStatusField($builder);
    }

    protected function addDossierStatusField($builder)
    {
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $data = $event->getData();
            $form = $event->getForm();
            $options = $form->getConfig()->getOptions();

            if ($data instanceof HasDossierStatusInterface
                && $data->getDossierStatussen()->count() < 1
                && null !== $options['openDossierStatusFormType']
            ) {
                $data->addDossierStatus(new Aanmelding());

                $form->add('dossierStatussen', CollectionType::class, [
                    'entry_type' => $options['openDossierStatusFormType'],
                    'entry_options' => [
                        'label' => false,
//                        'submit_button' => false,
                    ],
                    'label'=>'Dossierstatus',
                    'priority'=>100,
                ]);
            }
        });
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'openDossierStatusFormType' => null,
            'closeDossierStatusFormType' => null,
            'test'=>false,
        ]);
    }

//    public function getParent()
//    {
//        return BaseType::class;
//    }
}
