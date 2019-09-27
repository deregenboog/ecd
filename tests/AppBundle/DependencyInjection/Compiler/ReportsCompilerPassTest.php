<?php

namespace Tests\AppBundle\DependencyInjection\Compiler;

use AppBundle\DependencyInjection\Compiler\AbstractReportsCompilerPass;
use AppBundle\DependencyInjection\Compiler\ReportsCompilerPass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

class ReportsCompilerPassTest extends TestCase
{
    /**
     * @expectedException \AppBundle\Exception\AppException
     */
    public function testProcessWithoutServiceId()
    {
        $container = $this->createMock(ContainerBuilder::class);
        $pass = new NoServiceIdReportsCompilerPass();
        $pass->process($container);
    }

    /**
     * @expectedException \AppBundle\Exception\AppException
     */
    public function testProcessWithoutTagId()
    {
        $container = $this->createMock(ContainerBuilder::class);
        $pass = new NoTagIdReportsCompilerPass();
        $pass->process($container);
    }

    public function testProcessWithCategories()
    {
        $container = $this->createMock(ContainerBuilder::class);
        $definition = new Definition();
        $pass = new ReportsCompilerPass();

        $container->method('getDefinition')
            ->with($pass->getServiceId())
            ->willReturn($definition);
        $container->method('findTaggedServiceIds')
            ->with($pass->getTagId())
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

class NoServiceIdReportsCompilerPass extends AbstractReportsCompilerPass
{
    protected $serviceId;

    protected $tagId = 'tag_id';
}

class NoTagIdReportsCompilerPass extends AbstractReportsCompilerPass
{
    protected $serviceId = 'service_id';

    protected $tagId;
}
