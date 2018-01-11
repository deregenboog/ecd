<?php

namespace OekBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Form\EmailMessageType as AppEmailMessageType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use OekBundle\Entity\Deelnemer;

class EmailMessageType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $emails = [];
        if (isset($options['to'])) {
            foreach ($options['to'] as $deelnemer) {
                if ($deelnemer instanceof Deelnemer) {
                    $emails[] = $deelnemer->getKlant()->getEmail();
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

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'from' => null,
            'to' => [],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return AppEmailMessageType::class;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'oek_email_message';
    }
}
