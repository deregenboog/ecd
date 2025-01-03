<?php

namespace IzBundle\Form;

use AppBundle\Form\EmailMessageType;
use IzBundle\Entity\IzKlant;
use IzBundle\Entity\IzVrijwilliger;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IzEmailMessageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $emails = [];
        if (isset($options['to'])) {
            foreach ($options['to'] as $izDeelnemer) {
                switch (true) {
                    case $izDeelnemer instanceof IzKlant:
                        $emails[] = $izDeelnemer->getKlant()->getEmail();
                        break;
                    case $izDeelnemer instanceof IzVrijwilliger:
                        $emails[] = $izDeelnemer->getVrijwilliger()->getEmail();
                        break;
                    default:
                        break;
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
            ->add('from', HiddenType::class, ['data' => $options['from']])
            ->add('to', HiddenType::class, ['data' => implode(', ', $emails)])
            ->add('submit', SubmitType::class)
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
        return EmailMessageType::class;
    }
}
