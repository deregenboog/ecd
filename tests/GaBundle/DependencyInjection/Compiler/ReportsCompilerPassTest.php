<?php

namespace Tests\GaBundle\DependencyInjection\Compiler;

use GaBundle\DependencyInjection\Compiler\ReportsCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

class ReportsCompilerPassTest extends \PHPUnit_Framework_TestCase
{
    public function testProcess()
    {
        $form = new Definition();

        $container = $this->createMock(ContainerBuilder::class);
        $container->method('getDefinition')
            ->with('GaBundle\Form\RapportageType')
            ->willReturn($form);
        $container->method('findTaggedServiceIds')
            ->with('ga.rapportage')
            ->willReturn([
                'report_1' => [[
                    'category' => 'category_1',
                ]],
                'report_2' => [[
                    'category' => 'category_1',
                ]],
                'report_3' => [[
                    'category' => 'category_2',
                ]],
            ]);

        $pass = $this->createSUT();
        $pass->process($container);

        $expected = [
            'category_1' => [
                'report_1' => new Reference('report_1'),
                'report_2' => new Reference('report_2'),
            ],
            'category_2' => [
                'report_3' => new Reference('report_3'),
            ],
        ];

        $this->assertEquals($expected, $form->getArgument(0));
    }

    private function createSUT()
    {
        return new ReportsCompilerPass();
    }
}
