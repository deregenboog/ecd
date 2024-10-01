<?php

namespace AppBundle\Form;

use AppBundle\Entity\Medewerker;
use AppBundle\Form\MedewerkerType;
use Doctrine\ORM\EntityRepository;
use OekraineBundle\Entity\Locatie;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;

use Symfony\Component\Form\Event\SubmitEvent;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;


class MedewerkerSelectType extends AbstractType
{
//    public function getParent(): ?string
//    {
//        return EntityType::class;
//    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
//            'class'=>Medewerker::class,
            'preset' => false,
            'multiple'=>false,
            'required'=>false,
            'roles'=>[],
            'placeholder' => '',
        ]);

    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('medewerkers', EntityType::class, [
            'class' => Medewerker::class,
            'placeholder' => 'Selecteer een medewerker',
            'choice_label' => function(?Medewerker $medewerker) {
                return $medewerker ? $medewerker->getNaam() : '';
            },
            'multiple'=>false,
            'query_builder' => function (EntityRepository $er) use ($options) {
                $roles = $options['roles'];
                $query = $er->createQueryBuilder('m')
                    ->where('m.actief = true')
                    ->orderBy('m.voornaam');

                if(count($roles) > 0) {
                    $orX = $query->expr()->orX();
                    foreach($roles as $key => $role) {
                        $orX->add($query->expr()->like('m.roles', ':role' . $key));
                        $query->setParameter('role' . $key, '%' . $role . '%');
                    }
                    $query->andWhere($orX);
                }
                return $query;
            },
        ]);
//        $builder->addEventListener(FormEvents::SUBMIT, function (SubmitEvent $event) {
//            $data = $event->getData();
//            if (isset($data['medewerker'])
//                && is_array($data['medewerker'])
//                && count($data['medewerker']) === 1
//                && $data['medewerker'][0] === null)
//            {
//                $data['medewerker'] = null;
//            }
//            $event->setData($data);
//        });
    }

}