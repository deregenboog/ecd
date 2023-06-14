<?php

namespace Tests\InloopBundle\Event;

use AppBundle\Entity\Klant;
use AppBundle\Entity\Medewerker;
use InloopBundle\Entity\Intake;
use InloopBundle\Entity\Toegang;
use InloopBundle\Event\IntakeSubscriber;
use InloopBundle\Service\AccessUpdater;
use InloopBundle\Service\KlantDaoInterface;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Message;
use Twig\Environment;

class IntakeSubscriberTest extends TestCase
{
    private $klantDao;
    private $logger;
    private $twig;
    private $mailer;
    private $accessUpdater;
    public $informeleZorgEmail = 'informele_zorg@example.org';
    public $dagbestedingEmail = 'dagbesteding@example.org';
    public $inloophuisEmail = 'inloophuis@example.org';
    public $hulpverleningEmail = 'hulpverlening@example.org';

    protected function setUp(): void
    {
        $this->klantDao = $this->getMockForAbstractClass(KlantDaoInterface::class);
        $this->logger = $this->getMockForAbstractClass(LoggerInterface::class);
        $this->twig = $this->getMockBuilder(Environment::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->mailer = $this->getMockForAbstractClass(MailerInterface::class);
        $this->accessUpdater = $this->createMock(AccessUpdater::class);
    }

    public function testAccessAmocOnly()
    {
        $this->markTestIncomplete();

        return;

        $intake = new Intake(new Klant());
        $intake->setIntakedatum(new \DateTime('three weeks ago'));
        $intake->setInloophuis(true);
        $intake->setVerblijfsstatus();

        $subscriber = $this->createSUT();
        $subscriber->afterIntakeCreated(new GenericEvent($intake));

        $inloopToegang = new Toegang();
    }

    public function testEmailIsSentOnIntakeCreationWithServices()
    {
        $intake = new Intake(new Klant());
        $intake->setMedewerker((new Medewerker())->setEmail('info@example.com'));
        $intake->setInformeleZorg(true)->setInloophuis(true);

        $test = $this;
        $this->mailer->expects($this->once())
            ->method('send')
            ->with($this->callback(function (Message $message) {
                return $message instanceof Message;
            }))
        ;

        $subscriber = $this->createSUT();
        $subscriber->afterIntakeCreated(new GenericEvent($intake));
    }

    public function testNoEmailIsSentOnIntakeCreationWithoutServices()
    {
        $intake = new Intake(new Klant());
        $intake->setMedewerker((new Medewerker())->setEmail('info@example.com'));

        $this->mailer->expects($this->never())->method('send');

        $subscriber = $this->createSUT();
        $subscriber->afterIntakeCreated(new GenericEvent($intake));
    }

    public function testEmailSuccessIsLoggedAtDebugLevel()
    {
        $intake = new Intake(new Klant());
        $intake->setMedewerker((new Medewerker())->setEmail('info@example.com'));
        $intake->setDagbesteding(true)->setHulpverlening(true);

        $this->mailer->expects($this->once())->method('send');

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
        $subscriber->afterIntakeCreated(new GenericEvent($intake));
    }

    public function testEmailFailureIsLoggedAtErrorLevel()
    {
        $this->markTestSkipped();

        $intake = new Intake(new Klant());
        $intake->setMedewerker((new Medewerker())->setEmail('info@example.com'));
        $intake->setDagbesteding(true)->setHulpverlening(true)->setInloophuis(false);

        $this->mailer->expects($this->once())->method('send');

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
        $subscriber->afterIntakeCreated(new GenericEvent($intake));
    }

    protected function createSUT()
    {
        return new IntakeSubscriber(
            $this->klantDao,
            $this->logger,
            $this->twig,
            $this->mailer,
            $this->accessUpdater,
            $this->informeleZorgEmail,
            $this->dagbestedingEmail,
            $this->hulpverleningEmail
        );
    }
}
