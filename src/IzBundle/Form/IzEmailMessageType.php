<?php

namespace IzBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Entity\Klant;
use AppBundle\Filter\KlantFilter;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use AppBundle\Form\EmailMessageType;
use IzBundle\Entity\IzDeelnemer;
use IzBundle\Entity\IzKlant;
use IzBundle\Entity\IzVrijwilliger;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class IzEmailMessageType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
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
                        continue;
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
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'from' => null,
            'to' => [],
            'action' => '/iz_deelnemers/email_selectie',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return EmailMessageType::class;
    }
}
