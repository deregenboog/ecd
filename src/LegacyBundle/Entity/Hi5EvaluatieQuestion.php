<?php

namespace LegacyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Hi5EvaluatieQuestions.
 *
 * @ORM\Table(name="hi5_evaluatie_questions")
 * @ORM\Entity
 */
class Hi5EvaluatieQuestion
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $hi5_evaluatie_paragraph_id;

    /**
     * @ORM\Column(name="text", type="string", length=255)
     */
    private $text;
}
