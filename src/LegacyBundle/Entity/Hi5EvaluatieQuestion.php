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
     * @ORM\ManyToOne(targetEntity="Hi5EvaluatieParagraph")
     */
    private $hi5EvaluatieParagraph;

    /**
     * @ORM\Column(name="text", type="string", length=255, nullable=false)
     */
    private $text;
}
