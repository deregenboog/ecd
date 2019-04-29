<?php

namespace LegacyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Hi5Answers.
 *
 * @ORM\Table(name="hi5_answers")
 * @ORM\Entity
 */
class Hi5Answer
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="answer", type="string", length=255, nullable=false)
     */
    private $answer;

    /**
     * @ORM\ManyToOne(targetEntity="Hi5Question")
     */
    private $hi5Question;

    /**
     * @ORM\ManyToOne(targetEntity="Hi5AnswerType")
     */
    private $hi5AnswerType;
}
