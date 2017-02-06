<?php

namespace OekBundle\Form;

use Doctrine\ORM\EntityRepository;
use OekBundle\Entity\OekKlant;
use OekBundle\Form\Model\OekGroepModel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class OekGroepKlantType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('oekKlant', EntityType::class, [
            'class' => OekKlant::class,
            'query_builder' => function (EntityRepository $repository) use ($options) {
                return $repository->createQueryBuilder('klant')
                    ->where('klant NOT IN (:klanten)')
                    ->setParameter('klanten', $options['data']->getOekKlanten())
                    ;
            },
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => OekGroepModel::class,
        ]);
    }
}
