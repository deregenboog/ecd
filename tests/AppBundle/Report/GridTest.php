<?php

declare(strict_types=1);

namespace Tests\AppBundle\Report;

use AppBundle\Report\Grid;
use PHPUnit\Framework\TestCase;

class GridTest extends TestCase
{
    /**
     * @dataProvider dataProvider
     */
    public function testTableRendering(array $data, bool $ySort, ?string $yNullReplacement, bool $yTotals, array $output)
    {
        $grid = new Grid($data, ['Aantal' => 'aantal'], 'projectnaam');
        $grid->setYSort($ySort)
            ->setYNullReplacement($yNullReplacement)
            ->setYTotals($yTotals);

        $this->assertSame($output, $grid->render());
    }

    public function dataProvider()
    {
        return [
            [ // defaults
                [ // input
                    [
                        'projectnaam' => 'Project Y',
                        'aantal' => 1,
                    ],
                    [
                        'projectnaam' => 'Project X',
                        'aantal' => 2,
                    ],
                ],
                false, // ySort
                null, // yNullReplacement
                true, // yTotals
                [ // output
                    'Project Y' => [
                        'Aantal' => 1,
                    ],
                    'Project X' => [
                        'Aantal' => 2,
                    ],
                    'Totaal' => [
                        'Aantal' => 3,
                    ],
                ],
            ],
            [ // with ySort=true
                [ // input
                    [
                        'projectnaam' => 'Project Y',
                        'aantal' => 1,
                    ],
                    [
                        'projectnaam' => 'Project X',
                        'aantal' => 2,
                    ],
                ],
                true, // ySort
                null, // yNullReplacement
                true, // yTotals
                [ // output
                    'Project X' => [
                        'Aantal' => 2,
                    ],
                    'Project Y' => [
                        'Aantal' => 1,
                    ],
                    'Totaal' => [
                        'Aantal' => 3,
                    ],
                ],
            ],
            [ // with yNullReplacement='Overigen'
                [ // input
                    [
                        'projectnaam' => 'Project Y',
                        'aantal' => 1,
                    ],
                    [
                        'projectnaam' => '',
                        'aantal' => 3,
                    ],
                    [
                        'projectnaam' => 'Project X',
                        'aantal' => 2,
                    ],
                ],
                false, // ySort
                'Overigen', // yNullReplacement
                true, // yTotals
                [ // output
                    'Project Y' => [
                        'Aantal' => 1,
                    ],
                    'Project X' => [
                        'Aantal' => 2,
                    ],
                    'Overigen' => [
                        'Aantal' => 3,
                    ],
                    'Totaal' => [
                        'Aantal' => 6,
                    ],
                ],
            ],
            [ // with yTotals=false
                [ // input
                    [
                        'projectnaam' => 'Project Y',
                        'aantal' => 1,
                    ],
                    [
                        'projectnaam' => 'Project X',
                        'aantal' => 2,
                    ],
                ],
                false, // ySort
                null, // yNullReplacement
                false, // yTotals
                [ // output
                    'Project Y' => [
                        'Aantal' => 1,
                    ],
                    'Project X' => [
                        'Aantal' => 2,
                    ],
                ],
            ],
        ];
    }
}
