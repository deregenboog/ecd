<?php

namespace GaBundle\Form;

use AppBundle\Form\EmailMessageType as AppEmailMessageType;
use GaBundle\Entity\Klantdossier;
use GaBundle\Entity\Vrijwilligerdossier;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EmailMessageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $emails = [];
        if (isset($options['to'])) {
            foreach ($options['to'] as $dossier) {
                if ($dossier instanceof Klantdossier) {
                    $emails[] = $dossier->getKlant()->getEmail();
                } elseif ($dossier instanceof Vrijwilligerdossier) {
                    $emails[] = $dossier->getVrijwilliger()->getEmail();
                }
            }
        }

        // remove empty values
        $emails = array_filter($emails);
        // copy values to keys
        $emails = array_combine($emails, $emails);

        $builder
            ->remove('from')
            ->remove('to')
            ->remove('cc')
            ->remove('bcc')
            ->remove('file1')
            ->remove('file2')
            ->remove('file3')
            ->add('from', HiddenType::class, ['data' => $options['from']])
            ->add('to', HiddenType::class, ['data' => implode(', ', $emails)])
            ->add('submit', SubmitType::class, ['label' => 'Verzenden'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'from' => null,
            'to' => [],
        ]);
    }

    public function getParent(): ?string
    {
        return AppEmailMessageType::class;
    }

    public function getBlockPrefix(): string
    {
        return 'ga_email_message';
    }
}
