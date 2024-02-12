<?php

declare(strict_types=1);

namespace Tests\AppBundle\Report;

use AppBundle\Report\Grid;
use PHPUnit\Framework\TestCase;

class GridTest extends TestCase
{
    /**
     * @param array $input
     * @param array $output
     *
     * @dataProvider dataProvider
     */
    public function testTableRendering($input, $output)
    {
        $grid = new Grid($input, ['Aantal' => 'aantal'], 'projectnaam');
        $this->assertEquals($output, $grid->render());
    }

    public function dataProvider()
    {
        return [
            [ // call 1
                [ // input
                    [
                        'projectnaam' => 'Project X',
                        'aantal' => '1',
                    ],
                    [
                        'projectnaam' => 'Project Y',
                        'aantal' => '1',
                    ],
                ],
                [ // output
                    'Project X' => [
                        'Aantal' => 1,
                    ],
                    'Project Y' => [
                        'Aantal' => 1,
                    ],
                    'Totaal' => [
                        'Aantal' => 2,
                    ],
                ],
            ],
        ];
    }
}
