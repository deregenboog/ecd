<?php

namespace LegacyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Hi5IntakesAnswers.
 *
 * @ORM\Table(name="hi5_intakes_answers")
 *
 * @ORM\Entity
 */
class Hi5IntakesAnswer
{
    /**
     * @ORM\Column(name="id", type="integer")
     *
     * @ORM\Id
     *
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $hi5_intake_id;

    /**
     * @ORM\Column(type="integer")
     */
    private $hi5_answer_id;

    /**
     * @ORM\Column(name="hi5_answer_text", type="text", length=65535, nullable=true)
     */
    private $hi5AnswerText;
}
