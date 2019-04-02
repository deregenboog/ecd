<?php

namespace LegacyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Hi5IntakesAnswers.
 *
 * @ORM\Table(name="hi5_intakes_answers")
 * @ORM\Entity
 */
class Hi5IntakesAnswer
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Hi5Intake")
     */
    private $hi5Intake;

    /**
     * @ORM\ManyToOne(targetEntity="Hi5Answer")
     */
    private $hi5Answer;

    /**
     * @ORM\Column(name="hi5_answer_text", type="text", length=65535, nullable=true)
     */
    private $hi5AnswerText;
}
