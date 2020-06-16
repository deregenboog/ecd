<?php

namespace AppBundle\Form;

use AppBundle\Entity\Geslacht;
use AppBundle\Entity\Doelstelling;
use AppBundle\Model\Doelstellingcijfer;
use AppBundle\Repository\DoelstellingRepositoryInterface;
use AppBundle\Security\DoelstellingVoter;
use AppBundle\Service\DoelstellingDao;
use AppBundle\Service\DoelstellingDaoInterface;
use InloopBundle\Form\LocatieSelectType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DoelstellingType extends AbstractType
{
    private $choices = [];
    private $repos = [];
    private $options;
    private $securityAuthorizationChecker;
    /**
     * @var DoelstellingDao $doelstellingDao
     */
    private $doelstellingDao;

    public function __construct($securityAuthorizationChecker, $doelstellingDao, array $options = [])
    {

        $this->doelstellingDao = $doelstellingDao;

        $this->securityAuthorizationChecker = $securityAuthorizationChecker;
        $this->options = $options;
        $this->getRepositoryChoices($this->securityAuthorizationChecker,$this->doelstellingDao,$this->options,true);
        //this should be called again if data is loaded in case of an editted form...


    }

    private function getRepositoryChoices($securityAuthorizationChecker,DoelstellingDao $doelstellingDao,$options,$onlyAvailableOptions=true)
    {
        foreach ($options as $doelstellingRepo) {
//            if(!method_exists($doelstellingRepo,"getMethods")) continue;
            if(!$doelstellingRepo instanceof DoelstellingRepositoryInterface) continue;


//            $cijfers = $doelstellingRepo->getAvailableDoelstellingcijfers();

            $cijfers = $doelstellingDao->getAvailableDoelstellingcijfers($doelstellingRepo,$onlyAvailableOptions);

            $cat = $doelstellingRepo->getCategory();
            foreach ($cijfers as $c) {
                /**
                 * @var Doelstellingcijfer $c
                 */
//                $namespacename = $m->getDeclaringClass()->getName();
                $label = $c->getLabel();
                $classMethod = get_class($doelstellingRepo)."::".$c->getLabel();
                //$m->getDeclaringClass()->getName()."::".$m->getName();
                if($securityAuthorizationChecker->isGranted("edit",$classMethod))
                {
                    $this->choices[$cat][$label] = $classMethod;
                }
//                $this->choices[$cat][$label] = $classMethod;

            }
        }
    }
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $range = range((new \DateTime('previous year'))->modify('-1 year')->format('Y'), (new \DateTime('next year'))->format('Y'));
        $repo = null;
        $disabled = false;
        if(isset($options['data']) && null !== $options['data']->getRepository())
        {
            //edit.
            $this->getRepositoryChoices($this->securityAuthorizationChecker,$this->doelstellingDao,$this->options,false);
            $disabled = true;

        }
        $builder

            ->add('repository', ChoiceType::class, [
                'required' => true,
                'placeholder' => 'Selecteer een module',
                'choices' => $this->choices,
                'disabled'=>$disabled,

            ])
            ->add('jaar', ChoiceType::class, [
                'choices' => array_combine($range, $range),
            ])

        ;


        $builder->add('aantal',null,[
            'required'=>true,
            'label'=>'Aantal (prestatie)'
        ])
            ->add('submit', SubmitType::class, ['label' => 'Opslaan'])
            ;

    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Doelstelling::class,
//            'allow_extra_fields'=>true,
        ]);
    }


    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return BaseType::class;
    }

    /**
     * @return array
     */
    public function getRepos(): array
    {
        return $this->repos;
    }

}
