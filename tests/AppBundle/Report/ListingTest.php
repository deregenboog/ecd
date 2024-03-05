<?php

declare(strict_types=1);

namespace Tests\AppBundle\Report;

use AppBundle\Report\Listing;
use PHPUnit\Framework\TestCase;

class ListingTest extends TestCase
{
    /**
     * @dataProvider dataProvider
     */
    public function testTableRendering(array $input, array $output)
    {
        $listing = new Listing($input, ['Naam' => 'naam', 'Aantal registraties' => 'aantal']);
        $this->assertSame($output, $listing->render());
    }

    public function dataProvider()
    {
        return [
            [ // call 1
                [ // input
                    [
                        'naam' => 'Piet Jansen',
                        'aantal' => 1,
                    ],
                    [
                        'naam' => 'Jan Pietersen',
                        'aantal' => 1,
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
