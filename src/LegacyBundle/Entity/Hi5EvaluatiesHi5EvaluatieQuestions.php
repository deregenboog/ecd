<?php

namespace LegacyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Hi5EvaluatiesHi5EvaluatieQuestions.
 *
 * @ORM\Table(name="hi5_evaluaties_hi5_evaluatie_questions")
 *
 * @ORM\Entity
 */
class Hi5EvaluatiesHi5EvaluatieQuestions
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
     * @ORM\Column(name="hi5_evaluatie_id", type="integer")
     */
    private $hi5EvaluatieId;

    /**
     * @ORM\Column(name="hi5_evaluatie_question_id", type="integer")
     */
    private $hi5EvaluatieQuestionId;

    /**
     * @ORM\Column(name="hi5er_radio", type="integer")
     */
    private $hi5erRadio;

    /**
     * @ORM\Column(name="hi5er_details", type="text", length=65535)
     */
    private $hi5erDetails;

    /**
     * @ORM\Column(name="wb_radio", type="integer")
     */
    private $wbRadio;

    /**
     * @ORM\Column(name="wb_details", type="text", length=65535)
     */
    private $wbDetails;
}
