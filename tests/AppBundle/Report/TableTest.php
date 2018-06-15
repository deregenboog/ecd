<?php

namespace AppBundle\Report;

use PHPUnit\Framework\TestCase;

class TableTest extends TestCase
{
    /**
     * @param array $input
     * @param array $output
     *
     * @dataProvider dataProvider
     */
    public function testTableRendering($input, $output)
    {
        $table = new Table($input, 'kolom', 'projectnaam', 'aantal');
        $this->assertEquals($output, $table->render());
    }

    public function dataProvider()
    {
        return [
            [ // call 1
                [ // input
                    [
                        'kolom' => 'Caseload startdatum',
                        'projectnaam' => 'Project X',
                        'aantal' => '1',
                    ],
                    [
                        'kolom' => 'Caseload startdatum',
                        'projectnaam' => 'Project X',
                        'aantal' => '1',
                    ],
                ],
                [ // output
                    'Project X' => [
                        'Caseload startdatum' => 2,
                        'Totaal' => 2,
                    ],
                    'Totaal' => [
                        'Caseload startdatum' => 2,
                        'Totaal' => 2,
                    ],
                ],
            ],
            [ // call 2
                [ // input
                    [
                        'kolom' => 'Caseload startdatum',
                        'projectnaam' => 'Project X',
                        'aantal' => '1',
                    ],
                    [
                        'kolom' => 'Doelstelling',
                        'projectnaam' => 'Project X',
                        'aantal' => '1',
                    ],
                ],
                [ // output
                    'Project X' => [
                        'Caseload startdatum' => 1,
                        'Doelstelling' => 1,
                        'Totaal' => 2,
                    ],
                    'Totaal' => [
                        'Caseload startdatum' => 1,
                        'Doelstelling' => 1,
                        'Totaal' => 2,
                    ],
                ],
            ],
        ];
    }
}
