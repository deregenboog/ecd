<?php

namespace AppBundle\Model;

use Symfony\Component\HttpFoundation\File\File;

interface DocumentInterface
{
    public function getFilename();

    public function setFilename($filename);

    public function getFile();

    public function setFile(File $file);

//    public function getDiscr();
//
//    public function setDiscr(string $discr);
}
