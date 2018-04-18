<?php

namespace IzBundle\Form;

use AppBundle\Entity\Klant;
use AppBundle\Form\AppDateType;
use AppBundle\Form\BaseType;
use AppBundle\Form\CKEditorType;
use AppBundle\Form\KlantType;
use Doctrine\ORM\EntityRepository;
use IzBundle\Entity\IzKlant;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IzKlantType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var $izKlant IzKlant */
        $izKlant = $options['data'];

        if ($izKlant instanceof IzKlant
            && $izKlant->getKlant() instanceof Klant
            && $izKlant->getKlant()->getId()
        ) {
            $builder->add('klant', null, [
                'disabled' => true,
                'query_builder' => function (EntityRepository $repository) use ($izKlant) {
                    return $repository->createQueryBuilder('klant')
                        ->where('klant = :klant')
                        ->setParameter('klant', $izKlant->getKlant())
                    ;
                },
            ]);
        } else {
            $builder
                ->add('klant', KlantType::class, ['required' => true])
                ->get('klant')
                ->remove('opmerking')
                ->remove('geenPost')
                ->remove('geenEmail')
            ;
        }

        if ($izKlant->hasOpenHulpvragen() || $izKlant->hasActieveKoppelingen()) {
            $izKlant->setStatus(null);
        } else {
            $builder->add('status', DeelnemerstatusSelectType::class, [
                'required' => false,
            ]);
        }

        $builder
            ->add('datumAanmelding', AppDateType::class)
            ->add('organisatieAanmelder', null, [
                'required' => false,
            ])
            ->add('naamAanmelder', null, [
                'required' => false,
            ])
            ->add('emailAanmelder', null, [
                'required' => false,
            ])
            ->add('telefoonAanmelder', null, [
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
            'class' => IzKlant::class,
        ]);
    }

    public function getParent()
    {
        return BaseType::class;
    }
}
