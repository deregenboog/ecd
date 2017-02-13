<?php

namespace OdpBundle\Form;

use AppBundle\Form\AppDateType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use OdpBundle\Entity\OdpHuurovereenkomst;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use OdpBundle\Entity\OdpHuurovereenkomstAfsluiting;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OdpHuurovereenkomstAfsluitingType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('afsluitdatum', AppDateType::class, ['required' => true]);
        $builder->add('odpHuurovereenkomstAfsluiting', EntityType::class, [
            'class' => OdpHuurovereenkomstAfsluiting::class,
            'required' => true,
            'query_builder' => function(EntityRepository $repository) {
                return $repository
                    ->createQueryBuilder('afsluiting')
                    ->where('afsluiting.actief = true');
            }
        ]);
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
}
