<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

interface DossierStatusControllerInterface
{

    public function editDossierStatusAction($id): array;

    public function openAction(Request $request, $id): array;

    public function closeAction(Request $request, $id): array;

}