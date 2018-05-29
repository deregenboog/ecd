<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use AppBundle\Model\IdentifiableTrait;

/**
 * @ORM\Entity
 * @ORM\Table(name="attachments")
 */
class Attachment
{
    use IdentifiableTrait;

    /**
     * @ORM\Column(type="string")
     */
    public $model;

    /**
     * @ORM\Column(type="integer")
     */
    public $foreign_key;

    /**
     * @ORM\Column(type="string")
     */
    public $dirname;

    /**
     * @ORM\Column(type="string")
     */
    public $basename;

    /**
     * @ORM\Column(type="string")
     */
    public $checksum;

    /**
     * @ORM\Column(type="string")
     */
    public $group;

    /**
     * @ORM\Column(type="string")
     */
    public $alternative;

    /**
     * @ORM\Column(type="string")
     */
    public $title;

    /**
     * @ORM\Column(type="datetime")
     */
    public $created;

    /**
     * @ORM\Column(type="datetime")
     */
    public $modified;

    /**
     * @ORM\Column(type="integer")
     */
    public $user_id;

    /**
     * @ORM\Column(type="boolean")
     */
    public $is_active;
}
