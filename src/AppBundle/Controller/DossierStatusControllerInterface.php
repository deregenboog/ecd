<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

interface DossierStatusControllerInterface
{

    public function editDossierStatusAction($id);

    public function openAction(Request $request, $id);

    public function closeAction(Request $request, $id);

}