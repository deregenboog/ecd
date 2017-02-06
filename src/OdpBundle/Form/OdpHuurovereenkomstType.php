<?php

namespace OdpBundle\Form;

use AppBundle\Form\AppDateType;
use OdpBundle\Entity\OdpHuuraanbod;
use OdpBundle\Entity\OdpHuurovereenkomst;
use OdpBundle\Entity\OdpHuurverzoek;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OdpHuurovereenkomstType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if ($options['data'] instanceof OdpHuurovereenkomst) {
            if ($options['data']->getOdpHuuraanbod()) {
                $this->setOdpHuurverzoek($builder);
            } elseif ($options['data']->getOdpHuurverzoek()) {
                $this->setOdpHuuraanbod($builder);
            }
        } else {
            $this->setOdpHuuraanbod($builder);
            $this->setOdpHuurverzoek($builder);
        }

        $builder->add('startdatum', AppDateType::class);
        $builder->add('einddatum', AppDateType::class, ['required' => false]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => OdpHuurovereenkomst::class,
            'enabled_filters' => [],
        ]);
    }

    private function setOdpHuurverzoek(FormBuilderInterface $builder)
    {
        $builder->add('odpHuurverzoek', EntityType::class, [
            'class' => OdpHuurverzoek::class
        ]);
    }

    private function setOdpHuuraanbod(FormBuilderInterface $builder)
    {
        $builder->add('odpHuuraanbod', EntityType::class, [
            'class' => OdpHuuraanbod::class
        ]);
    }
}
