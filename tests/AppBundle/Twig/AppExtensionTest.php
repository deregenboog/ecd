<?php

declare(strict_types=1);

namespace Tests\AppBundle\Twig;

use AppBundle\Entity\Klant;
use AppBundle\Service\ECDHelper;
use AppBundle\Twig\AppExtension;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\RequestStack;

class AppExtensionTest extends TestCase
{
    /**
     * @var AppExtension
     */
    private $extension;

    protected function setUp(): void
    {
        $requestStack = $this->createMock(RequestStack::class);

        $this->extension = new AppExtension($requestStack, 'nl_NL', 'Admin', 'admin@example.org', new ECDHelper());
    }

    /**
     * @dataProvider personProvider
     */
    public function testVoornaamAchternaamFilter($persoon, $expectedVoornaamAchternaam)
    {
        $this->assertEquals($expectedVoornaamAchternaam, $this->extension->naamVoorAchter($persoon));
    }

    /**
     * @dataProvider personProvider
     */
    public function testAchternaamVoornaamFilter($persoon, $ignore, $expectedAchternaamVoornaam)
    {
        $this->assertEquals($expectedAchternaamVoornaam, $this->extension->naamAchterVoor($persoon));
    }

    public function personProvider()
    {
        return [
            [
                (new Klant())->setVoornaam('Jan-Jaap')->setRoepnaam('Jan')->setTussenvoegsel('van der')->setAchternaam('Brink'),
                'Jan-Jaap (Jan) van der Brink',
                'Brink, Jan-Jaap (Jan) van der',
            ],
            [
                (new Klant())->setVoornaam('Jan-Jaap')->setTussenvoegsel('van der')->setAchternaam('Brink'),
                'Jan-Jaap van der Brink',
                'Brink, Jan-Jaap van der',
            ],
            [
                (new Klant())->setVoornaam('Jan-Jaap')->setAchternaam('Brink'),
                'Jan-Jaap Brink',
                'Brink, Jan-Jaap',
            ],
            [
                (new Klant())->setAchternaam('Brink'),
                'Brink',
                'Brink',
            ],
        ];
    }
}
