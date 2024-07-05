<?php

namespace LegacyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Hi5EvaluatieParagraphs.
 *
 * @ORM\Table(name="hi5_evaluatie_paragraphs")
 *
 * @ORM\Entity
 */
class Hi5EvaluatieParagraph
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
     * @ORM\Column(name="text", type="string", length=255)
     */
    private $text;
}
