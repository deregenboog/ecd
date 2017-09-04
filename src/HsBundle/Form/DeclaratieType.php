<?php

namespace HsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Form\AppDateType;
use HsBundle\Entity\Declaratie;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use AppBundle\Form\MedewerkerType;
use AppBundle\Form\BaseType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use HsBundle\Entity\Document;

class DeclaratieType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('medewerker', MedewerkerType::class)
            ->add('datum', AppDateType::class, ['data' => new \DateTime('today')])
            ->add('declaratieCategorie', null, [
                'label' => 'Declaratiecategorie',
                'placeholder' => 'Selecteer een declaratiecategorie',
            ])
            ->add('info')
            ->add('bedrag', MoneyType::class)
        ;

        if (!$options['data']->getDocument()) {
            $builder
                ->add('file', FileType::class, [
                    'label' => 'Document',
                    'mapped' => false,
                    'required' => false,
                ])
                ->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
                    if ($file = $event->getForm()->get('file')->getData()) {
                        $declaratie = $event->getData();
                        $document = new Document();
                        $document->setFile($file);
                        $declaratie->setDocument($document);
                    }
                })
            ;
        }

        $builder->add('submit', SubmitType::class);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Declaratie::class,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return BaseType::class;
    }
}
