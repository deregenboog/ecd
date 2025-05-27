<?php

namespace PfoBundle\Form;

use AppBundle\Form\AppDateType;
use AppBundle\Form\BaseType;
use AppBundle\Form\IncidentType as AppIncidentType;
use AppBundle\Form\JaNeeType;
use PfoBundle\Entity\Incident;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IncidentType extends AppIncidentType
{
    protected $entityType = Incident::class;
}
