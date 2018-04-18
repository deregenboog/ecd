<?php

namespace IzBundle\Form;

use AppBundle\Entity\Vrijwilliger;
use AppBundle\Form\AppDateType;
use AppBundle\Form\AppTextareaType;
use AppBundle\Form\BaseType;
use AppBundle\Form\DummyChoiceType;
use AppBundle\Form\VrijwilligerType;
use IzBundle\Entity\IzVrijwilliger;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Form\CKEditorType;

class IzVrijwilligerType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var $izVrijwilliger IzVrijwilliger */
        $izVrijwilliger = $options['data'];

        if ($izVrijwilliger instanceof IzVrijwilliger
            && $izVrijwilliger->getVrijwilliger() instanceof Vrijwilliger
            && $izVrijwilliger->getVrijwilliger()->getId()
        ) {
            $builder->add('vrijwilliger', DummyChoiceType::class, [
                'dummy_label' => (string) $izVrijwilliger,
            ]);
        } else {
            $builder
                ->add('vrijwilliger', VrijwilligerType::class, ['required' => true])
                ->get('vrijwilliger')
            ;
        }

        if ($izVrijwilliger->hasOpenHulpaanbiedingen() || $izVrijwilliger->hasActieveKoppelingen()) {
            $izVrijwilliger->setStatus(null);
        } else {
            $builder->add('status', DeelnemerstatusSelectType::class, [
                'required' => false,
            ]);
        }

        $builder
            ->add('datumAanmelding', AppDateType::class)
            ->add('binnengekomenVia', null, [
                'required' => false,
            ])
            ->add('notitie', CKEditorType::class, [
                'required' => false,
            ])
            ->add('naamContactpersoon', null, [
                'required' => false,
            ])
            ->add('telefoonContactpersoon', null, [
                'required' => false,
            ])
            ->add('submit', SubmitType::class)
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'class' => IzVrijwilliger::class,
        ]);
    }

    public function getParent()
    {
        return BaseType::class;
    }
}
