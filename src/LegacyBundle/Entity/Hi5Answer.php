<?php

namespace LegacyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Hi5Answers.
 *
 * @ORM\Table(name="hi5_answers")
 *
 * @ORM\Entity
 */
class Hi5Answer
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     *
     * @ORM\Id
     *
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="answer", type="string", length=255)
     */
    private $answer;

    /**
     * @ORM\Column(type="integer")
     */
    private $hi5_question_id;

    /**
     * @ORM\Column(type="integer")
     */
    private $hi5_answer_type_id;
}
