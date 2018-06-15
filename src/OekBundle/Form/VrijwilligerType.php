<?php

namespace OekBundle\Form;

use AppBundle\Entity\Vrijwilliger as AppVrijwilliger;
use AppBundle\Form\BaseType;
use AppBundle\Form\VrijwilligerType as AppVrijwilligerType;
use Doctrine\ORM\EntityRepository;
use OekBundle\Entity\Vrijwilliger;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Form\MedewerkerType;
use AppBundle\Form\DummyChoiceType;

class VrijwilligerType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var $vrijwilliger Vrijwilliger */
        $vrijwilliger = $options['data'];

        if ($vrijwilliger instanceof Vrijwilliger
            && $vrijwilliger->getVrijwilliger() instanceof AppVrijwilliger
            && $vrijwilliger->getVrijwilliger()->getId()
        ) {
            $builder->add('vrijwilliger', DummyChoiceType::class, [
                'dummy_label' => (string) $vrijwilliger,
            ]);
        } else {
            $builder
                ->add('vrijwilliger', AppVrijwilligerType::class)
                ->get('vrijwilliger')
                ->remove('opmerking')
                ->remove('geenPost')
                ->remove('geenEmail')
            ;
        }

        $builder
            ->add('medewerker', MedewerkerType::class)
            ->add('submit', SubmitType::class)
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Vrijwilliger::class,
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
