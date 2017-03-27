<?php

namespace OekBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Form\EmailMessageType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use OekBundle\Entity\OekKlant;

class OekEmailMessageType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $emails = [];
        if (isset($options['to'])) {
            foreach ($options['to'] as $oekKlant) {
                if ($oekKlant instanceof OekKlant) {
                    $emails[] = $oekKlant->getKlant()->getEmail();
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
        return EmailMessageType::class;
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return \AppBundle\Form\BaseType::class;
    }
}
