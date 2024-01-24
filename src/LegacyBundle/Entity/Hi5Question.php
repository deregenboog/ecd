<?php

namespace LegacyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Hi5Questions.
 *
 * @ORM\Table(name="hi5_questions")
 * @ORM\Entity
 */
class Hi5Question
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @ORM\Column(name="question", type="string", length=255)
     */
    private $question;

    /**
     * @ORM\Column(name="category", type="string", length=255)
     */
    private $category;

    /**
     * @ORM\Column(name="`order`", type="integer")
     */
    private $order;
}
