<?php

namespace AppBundle\Report;

use PHPUnit\Framework\TestCase;

class ListingTest extends TestCase
{
    /**
     * @param array $input
     * @param array $output
     *
     * @dataProvider dataProvider
     */
    public function testTableRendering($input, $output)
    {
        $listing = new Listing($input, ['Naam' => 'naam', 'Aantal registraties' => 'aantal']);
        $this->assertEquals($output, $listing->render());
    }

    public function dataProvider()
    {
        return [
            [ // call 1
                [ // input
                    [
                        'naam' => 'Piet Jansen',
                        'aantal' => '1',
                    ],
                    [
                        'naam' => 'Jan Pietersen',
                        'aantal' => '1',
                    ],
                ],
                [ // output
                    1 => [
                        'Naam' => 'Piet Jansen',
                        'Aantal registraties' => 1,
                    ],
                    2 => [
                        'Naam' => 'Jan Pietersen',
                        'Aantal registraties' => 1,
                    ],
                ],
            ],
        ];
    }
}
