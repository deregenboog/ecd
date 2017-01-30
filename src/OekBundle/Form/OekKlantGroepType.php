<?php

namespace OekBundle\Form;

use Doctrine\ORM\EntityRepository;
use OekBundle\Form\Model\OekKlantModel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use OekBundle\Entity\OekGroep;

class OekKlantGroepType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('oekGroep', EntityType::class, [
            'class' => OekGroep::class,
            'placeholder' => 'Selecteer een groep',
            'query_builder' => function (EntityRepository $repository) use ($options) {
                return $repository->createQueryBuilder('groep')
                    ->where('groep NOT IN (:groepen)')
                    ->setParameter('groepen', $options['data']->getOekGroepen())
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
            'data_class' => OekKlantModel::class,
        ]);
    }
}
