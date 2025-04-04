<?php

declare(strict_types=1);

namespace ScipBundle\Security;

use AppBundle\Entity\Medewerker;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;
use ScipBundle\Entity\Project;
use ScipBundle\Entity\Toegangsrecht;
use ScipBundle\Service\ToegangsrechtDaoInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;

class ProjectVoterTest extends TestCase
{
    public function testAdminHasAccess()
    {
        $token = $this->getMockForAbstractClass(TokenInterface::class);

        $decisionManager = $this->getMockForAbstractClass(AccessDecisionManagerInterface::class);
        $decisionManager->expects($this->once())
            ->method('decide')
            ->with($token, ['ROLE_SCIP_BEHEER'])
            ->willReturn(true);

        $toegangsrechtenDao = $this->getMockForAbstractClass(ToegangsrechtDaoInterface::class);

        $voter = new ProjectVoter($decisionManager, $toegangsrechtenDao);
        $this->assertEquals(VoterInterface::ACCESS_GRANTED, $voter->vote($token, new Project(), [Permissions::ACCESS]));
    }

    public function testUserHasAccessIfAccessToProject()
    {
        $medewerker = new Medewerker();
        $token = $this->getMockForAbstractClass(TokenInterface::class);
        $token->expects($this->once())->method('getUser')->willReturn($medewerker);

        $decisionManager = $this->getMockForAbstractClass(AccessDecisionManagerInterface::class);
        $decisionManager->expects($this->once())
            ->method('decide')
            ->with($token, ['ROLE_SCIP_BEHEER'])
            ->willReturn(false);

        $project = new Project();

        $toegangsrecht = new Toegangsrecht();
        $toegangsrecht->setProjecten(new ArrayCollection([$project]));

        $toegangsrechtenDao = $this->getMockForAbstractClass(ToegangsrechtDaoInterface::class);
        $toegangsrechtenDao->expects($this->once())
            ->method('findOneByMedewerker')
            ->with($medewerker)
            ->willReturn($toegangsrecht);

        $voter = new ProjectVoter($decisionManager, $toegangsrechtenDao);
        $this->assertEquals(VoterInterface::ACCESS_GRANTED, $voter->vote($token, $project, [Permissions::ACCESS]));
    }

    public function testUserHasNoAccessIfNoAccessToProject()
    {
        $medewerker = new Medewerker();
        $token = $this->getMockForAbstractClass(TokenInterface::class);
        $token->expects($this->once())->method('getUser')->willReturn($medewerker);

        $decisionManager = $this->getMockForAbstractClass(AccessDecisionManagerInterface::class);
        $decisionManager->expects($this->once())
            ->method('decide')
            ->with($token, ['ROLE_SCIP_BEHEER'])
            ->willReturn(false);

        $project1 = new Project();
        $project2 = new Project();

        $toegangsrecht = new Toegangsrecht();
        $toegangsrecht->setProjecten(new ArrayCollection([$project1]));

        $toegangsrechtenDao = $this->getMockForAbstractClass(ToegangsrechtDaoInterface::class);
        $toegangsrechtenDao->expects($this->once())
            ->method('findOneByMedewerker')
            ->with($medewerker)
            ->willReturn($toegangsrecht);

        $voter = new ProjectVoter($decisionManager, $toegangsrechtenDao);
        $this->assertEquals(VoterInterface::ACCESS_DENIED, $voter->vote($token, $project2, [Permissions::ACCESS]));
    }
}
