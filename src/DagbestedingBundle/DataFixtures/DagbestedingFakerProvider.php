<?php

namespace DagbestedingBundle\DataFixtures;
use DagbestedingBundle\Entity\Project;
use Faker\Generator;

class DagbestedingFakerProvider
{

    private $generator;
    private $usedElements = [];

    public function __construct(Generator $generator)
    {

        $this->generator = $generator;
        $this->usedElements = [];//het is een wat slordige implementatie.
    }

    public function uniqueElements(array $elements, int $count = 1)
    {
        //eigenlijk wil je wel dat er vanuit andere bundles referenties gebruikt kunnen worden naar eerder gebruikte elementen. maar de aanroepende class is niet bekend
        // dus je weet niet wanneer je moet resetten. Niet per aanroep, want dan is het sowieso niet uniek,

        try {
            $unusedElements = array_udiff($elements, $this->usedElements, function ($a, $b) {
                return spl_object_hash($a) <=> spl_object_hash($b);
            });
            $chosenElements = $this->generator->randomElements($unusedElements, $count);
            $this->usedElements = array_merge($this->usedElements, $chosenElements);

            if(count($chosenElements) < 1)
            {
                $b = $chosenElements;
            }
            return $chosenElements[0];
        }
        catch(\LengthException $e)
        {
            $s = $e;

        }
    }
}

