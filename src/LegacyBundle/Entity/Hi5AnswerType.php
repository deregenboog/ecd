<?php

namespace LegacyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Hi5AnswerTypes.
 *
 * @ORM\Table(name="hi5_answer_types")
 * @ORM\Entity
 */
class Hi5AnswerType
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @ORM\Column(name="answer_type", type="string", length=255)
     */
    private $answerType;
}
