<?php

namespace InloopBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpFoundation\Request;

class TestCommand extends ContainerAwareCommand
{
    private $kernel;

    protected function configure()
    {
        $this->setName('inloop:test');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $klantDao = $this->getContainer()->get('InloopBundle\Service\KlantDao');
        $locatieDao = $this->getContainer()->get('InloopBundle\Service\LocatieDao');

        $locaties = $locatieDao->findAll();

//         $locaties = [$locatieDao->find(21)];
//         $klanten = [$klantDao->find(5612462)];

        foreach (range(1, 500) as $page) {
            $klanten = $klantDao->findAll($page);
            foreach ($klanten as $klant) {
                foreach ($locaties as $locatie) {
                    if (0 !== rand(0, 10)) {
                        $output->writeln('Skipping...');
                        continue;
                    }

                    $oldUrl = sprintf('http://localhost/registraties/jsonCanRegister/%d/%d', $klant->getId(), $locatie->getId());
                    $oldJson = $this->getContent($oldUrl);

                    $newUrl = sprintf('http://localhost/inloop/registraties/jsonCanRegister/%d/%d', $klant->getId(), $locatie->getId());
                    $newJson = $this->getContent($newUrl);

                    if ($oldJson === $newJson) {
                        $output->writeln('Oud:   '.json_decode($oldJson));
                        $output->writeln('Nieuw: '.json_decode($newJson));
                        $output->writeln("Klant {$klant->getId()}, locatie {$locatie->getId()}: OK!");
                        continue;
                    }
                    $output->writeln("Klant {$klant->getId()}, locatie {$locatie->getId()}: NOT OK!");
                    var_dump($oldJson, $newJson);
                    die;
                }
            }
        }

//         $output->writeln("Reden '{$this->redenPattern}' is niet uniek!");
    }

    private function getContent($url)
    {
//         if (!$this->kernel) {
//             $this->kernel = $this->getContainer()->get('kernel');
//         }

//         $request = Request::create($url);
//         $response = $this->kernel->handle($request);

//         return $response->getContent();

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $content = curl_exec($curl);
        curl_close($curl);

        return json_encode(str_replace('>', '\\u003E', trim($content)));
    }
}
