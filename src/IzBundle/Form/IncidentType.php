<?php

namespace IzBundle\Form;

use AppBundle\Form\AppDateType;
use AppBundle\Form\BaseType;
use AppBundle\Form\JaNeeType;
use IzBundle\Entity\Incident;
use InloopBundle\Form\LocatieSelectType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Form\IncidentType as AppIncidentType;

class IncidentType extends AppIncidentType
{
    protected $entityType = Incident::class;
}
