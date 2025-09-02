<?php
namespace AppBundle\Security;
use AppBundle\Entity\Medewerker;
use Doctrine\ORM\EntityManagerInterface;
use Hslavich\OneloginSamlBundle\Security\User\SamlUserFactoryInterface;
use Hslavich\OneloginSamlBundle\Security\User\SamlUserInterface;

class SamlUserFactory implements SamlUserFactoryInterface
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function createUser($username, array $attributes = []): SamlUserInterface
    {
        $guid = $attributes['http://schemas.microsoft.com/identity/claims/objectidentifier'][0];
        $email = $attributes['http://schemas.xmlsoap.org/ws/2005/05/identity/claims/emailaddress'][0];
        
        // Zoek eerst bestaande user op email (oude identifier)
        $repository = $this->entityManager->getRepository(Medewerker::class);
        $existingUser = $repository->findOneByEmail($email);
        
        if ($existingUser) {
            // Bestaande user gevonden - migreer naar GUID username
            $existingUser->setUsername($guid);
            $existingUser->setGuid($guid);
            return $existingUser;
        }
        
        // Geen bestaande user - maak nieuwe aan
        $medewerker = new Medewerker();
        $medewerker->setUsername($guid);
        $medewerker->setGuid($guid);
        $medewerker->setEersteBezoek(new \DateTime());
        $medewerker->setLaatsteBezoek(new \DateTime());
        return $medewerker;
    }
}