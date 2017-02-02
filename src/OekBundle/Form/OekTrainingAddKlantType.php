<?php

namespace OekBundle\Form;

use Doctrine\ORM\EntityRepository;
use OekBundle\Entity\OekKlant;
use OekBundle\Form\Model\OekTrainingFacade;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class OekTrainingAddKlantType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('oekKlant', EntityType::class, [
            'label' => 'Deelnemer',
            'placeholder' => 'Selecteer een deelnemer',
            'class' => OekKlant::class,
            'query_builder' => function (EntityRepository $repository) use ($options) {
                /* @var OekTrainingFacade $oekTraining */
                $oekTraining = $options['data'];

                return $repository->createQueryBuilder('klant')
                    ->innerJoin('klant.oekGroepen', 'oekGroep', 'WITH', 'oekGroep = :groep') // is member of group
                    ->where('klant NOT IN (:klanten)') // is not member of training
                    ->setParameter('groep', $oekTraining->getOekGroep())
                    ->setParameter('klanten', $oekTraining->getOekKlanten())
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
            'data_class' => OekTrainingFacade::class,
        ]);
    }
}
