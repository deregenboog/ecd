<?php

namespace InloopBundle\Form;

use InloopBundle\Entity\Document;


class DocumentType extends DocumentTypeAbstract
{
    protected $dataClass = Document::class;

}
