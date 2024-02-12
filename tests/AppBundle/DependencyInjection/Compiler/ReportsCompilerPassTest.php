<?php

declare(strict_types=1);

namespace Tests\AppBundle\DependencyInjection\Compiler;

use AppBundle\DependencyInjection\Compiler\ReportsCompilerPass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

class ReportsCompilerPassTest extends TestCase
{
    public function testProcessWithCategories()
    {
        $serviceId = 'AppBundle\Form\RapportageType';
        $tagId = 'app.rapportage';

        $container = $this->createMock(ContainerBuilder::class);
        $definition = new Definition();
        $pass = new ReportsCompilerPass($serviceId, $tagId);

        $container->method('getDefinition')
            ->with($serviceId)
            ->willReturn($definition);
        $container->method('findTaggedServiceIds')
            ->with($tagId)
            ->willReturn([
                'report_1' => [[]],
                'report_2' => [[
                    'category' => 'category_1',
                ]],
                'report_3' => [[
                    'category' => 'category_2',
                ]],
            ]);

        $pass->process($container);

        $expected = [
            'report_1' => new Reference('report_1'),
            'category_1' => [
                'report_2' => new Reference('report_2'),
            ],
            'category_2' => [
                'report_3' => new Reference('report_3'),
            ],
        ];

        $this->assertEquals($expected, $definition->getArgument(0));
    }
}
