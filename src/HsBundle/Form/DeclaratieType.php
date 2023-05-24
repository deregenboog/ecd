<?php

namespace HsBundle\Form;

use AppBundle\Form\AppDateType;
use AppBundle\Form\BaseType;
use HsBundle\Entity\Declaratie;
use HsBundle\Entity\Document;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DeclaratieType extends AbstractType
{
    use MedewerkerTypeTrait;

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->addMedewerkerType($builder, $options);

        $builder
            ->add('datum', AppDateType::class)
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
    public function getParent(): ?string
    {
        return BaseType::class;
    }
}
