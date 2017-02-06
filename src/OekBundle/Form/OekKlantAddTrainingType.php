<?php

namespace OekBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use OekBundle\Form\Model\OekKlantFacade;
use OekBundle\Entity\OekTraining;

class OekKlantAddTrainingType extends AbstractType
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
                /* @var OekKlantFacade $oekKlant */
                $oekKlant = $options['data'];

                $builder = $repository->createQueryBuilder('training')
                     ->where('training.oekGroep IN (:groepen)') // is member of group
                     ->orderBy('training.naam')
                     ->setParameter('groepen', $oekKlant->getOekGroepen())
                ;

                if (count($oekKlant->getOekTrainingen()) > 0) {
                    $builder
                        ->andWhere('training NOT IN (:trainingen)') // is not member of training
                        ->setParameter('trainingen', $oekKlant->getOekTrainingen())
                    ;
                }

                return $builder;
            },
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => OekKlantFacade::class,
        ]);
    }
}
