<?php

namespace OekraineBundle\Form;

use OekraineBundle\Entity\Document;


class DocumentType extends DocumentTypeAbstract
{
    protected $dataClass = Document::class;

}
