<?php

namespace LegacyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * I18n.
 *
 * @ORM\Table(name="i18n", indexes={@ORM\Index(name="locale", columns={"locale"}), @ORM\Index(name="model", columns={"model"}), @ORM\Index(name="row_id", columns={"foreign_key"}), @ORM\Index(name="field", columns={"field"})})
 *
 * @ORM\Entity
 */
class I18n
{
    /**
     * @var string
     *
     * @ORM\Column(name="locale", type="string", length=6)
     */
    private $locale;

    /**
     * @var string
     *
     * @ORM\Column(name="model", type="string", length=255)
     */
    private $model;

    /**
     * @var int
     *
     * @ORM\Column(name="foreign_key", type="integer")
     */
    private $foreignKey;

    /**
     * @var string
     *
     * @ORM\Column(name="field", type="string", length=255)
     */
    private $field;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text", length=65535, nullable=true)
     */
    private $content;

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
}
