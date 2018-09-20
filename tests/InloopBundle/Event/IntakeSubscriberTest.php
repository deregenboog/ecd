<?php

namespace Tests\InloopBundle\Event;

use AppBundle\Entity\Klant;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Event\LifecycleEventArgs;
use InloopBundle\Entity\Intake;
use InloopBundle\Event\IntakeSubscriber;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;

class IntakeSubscriberTest extends TestCase
{
    private $logger;
    private $templating;
    private $mailer;
    public $informeleZorgEmail = 'informele_zorg@example.org';
    public $dagbestedingEmail = 'dagbesteding@example.org';
    public $inloophuisEmail = 'inloophuis@example.org';
    public $hulpverleningEmail = 'hulpverlening@example.org';

    protected function setUp()
    {
        $this->logger = $this->getMockForAbstractClass(LoggerInterface::class);
        $this->templating = $this->getMockForAbstractClass(EngineInterface::class);
        $this->mailer = $this->getMockBuilder(\Swift_Mailer::class)
            ->disableOriginalConstructor()
            ->setMethods(['send'])
            ->getMock();
    }

    public function testEmailIsSentOnIntakeCreationWithServices()
    {
        $intake = new Intake(new Klant());
        $intake->setInformeleZorg(true)->setInloophuis(true);

        $entityManager = $this->createMock(EntityManager::class);

        $test = $this;
        $this->mailer->expects($this->once())
            ->method('send')
            ->with($this->callback(function ($message) use ($test) {
                return $message instanceof \Swift_Message
                    && $message->getTo() === [
                        $test->informeleZorgEmail => null,
                        $test->inloophuisEmail => null,
                    ]
                ;
            }))
        ;

        $subscriber = $this->createSUT();
        $subscriber->postPersist(new LifecycleEventArgs($intake, $entityManager));
    }

    public function testNoEmailIsSentOnIntakeCreationWithoutServices()
    {
        $intake = new Intake(new Klant());

        $entityManager = $this->createMock(EntityManager::class);

        $this->mailer->expects($this->never())
            ->method('send')
        ;

        $subscriber = $this->createSUT();
        $subscriber->postPersist(new LifecycleEventArgs($intake, $entityManager));
    }

    public function testEmailSuccessIsLoggedAtDebugLevel()
    {
        $intake = new Intake(new Klant());
        $intake->setDagbesteding(true)->setHulpverlening(true);

        $entityManager = $this->createMock(EntityManager::class);

        $this->mailer->expects($this->once())
            ->method('send')
            ->willReturn(2)
        ;

        $this->logger->expects($this->once())
            ->method('debug')
            ->with(
                $this->equalTo('Email intake verzonden'),
                $this->equalTo([
                    'intake' => $intake->getId(),
                    'to' => [
                        $this->dagbestedingEmail,
                        $this->hulpverleningEmail,
                    ],
                ])
            )
        ;

        $subscriber = $this->createSUT();
        $subscriber->postPersist(new LifecycleEventArgs($intake, $entityManager));
    }

    public function testEmailFailureIsLoggedAtErrorLevel()
    {
        $intake = new Intake(new Klant());
        $intake->setDagbesteding(true)->setHulpverlening(true);

        $entityManager = $this->createMock(EntityManager::class);

        $this->mailer->expects($this->once())
            ->method('send')
            ->willReturn(0)
        ;

        $this->logger->expects($this->once())
            ->method('error')
            ->with(
                $this->equalTo('Email intake kon niet worden verzonden'),
                $this->equalTo([
                    'intake' => $intake->getId(),
                    'to' => [
                        $this->dagbestedingEmail,
                        $this->hulpverleningEmail,
                    ],
                ])
            )
        ;

        $subscriber = $this->createSUT();
        $subscriber->postPersist(new LifecycleEventArgs($intake, $entityManager));
    }

    protected function createSUT()
    {
        return new IntakeSubscriber(
            $this->logger,
            $this->templating,
            $this->mailer,
            $this->informeleZorgEmail,
            $this->dagbestedingEmail,
            $this->inloophuisEmail,
            $this->hulpverleningEmail
        );
    }
}
