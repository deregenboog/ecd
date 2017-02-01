<?php

namespace OekBundle\Form;

use Doctrine\ORM\EntityRepository;
use OekBundle\Entity\OekTraining;
use OekBundle\Form\Model\OekKlantModel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class OekKlantTrainingType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('oekTraining', EntityType::class, [
            'label' => 'Training',
            'placeholder' => 'Selecteer een training',
            'class' => OekTraining::class,
            'query_builder' => function (EntityRepository $repository) use ($options) {
                return $repository->createQueryBuilder('training')
                    ->where('training IN (:groepsTrainingen)')
                    ->andWhere('training NOT IN (:trainingen)')
                    ->setParameter('groepsTrainingen', $options['data']->getOekGroepsTrainingen())
                    ->setParameter('trainingen', $options['data']->getOekTrainingen())
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
