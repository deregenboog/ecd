<?php

namespace AppBundle\Form;

use AppBundle\Entity\GgwGebied;
use AppBundle\Filter\DownloadVrijwilligersFilter;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DownloadVrijwilligersType extends AbstractType
{
   private $choices = [];
    public function __construct(array $entitymanager = [],$exports=null)
    {
        $this->getOnderdeelChoices($exports);

    }

    private function getOnderdeelChoices($exports) {
        foreach($exports as $e)
        {
            $this->choices[$e->getFriendlyName()] = $e->getServiceId();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('onderdeel', ChoiceType::class,
                [
                    'required' => true,
                    'multiple' => true,
                    'expanded'=> true,
                    'choices'=>$this->choices,
                ]
            )
            ->add('download', SubmitType::class)
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
//        $resolver->setDefaults([
//            'label' => 'Onderdeel',
//            'data_class'=> DownloadVrijwilligersFilter::class,
//            'required' => true,
//            'multiple' => true,
//            'expanded'=> true,
//            'query_builder' => function (EntityRepository $repository) {
//                return $repository->createQueryBuilder('postcodegebied')
//                    ->orderBy('postcodegebied.naam');
//            },
//        ]);
    }



    /**
     * {@inheritdoc}
     */
//    public function getParent(): ?string
//    {
//        return FormType::class;
//    }
}
