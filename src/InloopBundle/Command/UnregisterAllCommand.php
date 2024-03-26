<?php

namespace InloopBundle\Command;

use AppBundle\Util\DateTimeUtil;
use InloopBundle\Service\RegistratieDaoInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UnregisterAllCommand extends \Symfony\Component\Console\Command\Command
{
    /**
     * @var \DateTime
     */
    private $now;

    /**
     * @var RegistratieDaoInterface
     */
    private $registratieDao;

    public function __construct(\InloopBundle\Service\RegistratieDao $registratieDao)
    {
        $this->registratieDao = $registratieDao;
        parent::__construct();
    }

    protected function configure()
    {
        $this->setName('inloop:unregister:all');
    }

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->now = new \DateTime();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $hour = (int) $this->now->format('H');

        if ($hour >= 12 && $hour <= 15) {
            $output->writeln('Automatically checking out all visitors of night locations');
            $this->automaticCheckOut(RegistratieDaoInterface::TYPE_NIGHT, $output);
        } elseif ($hour >= 0 && $hour <= 3) {
            $output->writeln('Automatically checking out all visitors of day locations');
            $this->automaticCheckOut(RegistratieDaoInterface::TYPE_DAY, $output);
        } else {
            $output->writeln('This command must be run between 0:00 and 3:00 or between 12:00 and 15:00');

            return 0;
        }

        $output->writeln('Complete!');
        return 0;
    }

    public function automaticCheckOut($type, OutputInterface $output)
    {
        $registrations = $this->registratieDao->findAutoCheckoutCandidates($type);
        $output->writeln(sprintf('Processing %d registrations', count($registrations)));

        $closingTimes = [];
        foreach ($registrations as $registration) {
            $locationId = $registration->getLocatie()->getId();
            $dayOfWeek = $registration->getBinnen()->format('w');

            if (!isset($closingTimes[$locationId][$dayOfWeek])) {
                // cache closing time
                $closingTimes[$locationId][$dayOfWeek] = $registration->getLocatie()->getClosingTimeByDayOfWeek($dayOfWeek);
            }

            $closingTime = $closingTimes[$locationId][$dayOfWeek];

            // ignore if there is no closing time for this day
            if (!$closingTime) {
                continue;
            }

            $buiten = DateTimeUtil::combine($registration->getBinnen(), $closingTime);

            // ignore if location is still open
            if ($this->now < $buiten) {
                continue;
            }

            // take closing time of next day if checkin is greater than checkout
            if ($registration->getBinnen() > $buiten) {
                $nextDay = (clone $registration->getBinnen())->modify('+1 day');
                $closingTime = $registration->getLocatie()->getClosingTimeByDayOfWeek($nextDay->format('w'));

                // ignore if location is not open on the next day
                if (!$closingTime) {
                    continue;
                }

                $buiten = DateTimeUtil::combine($nextDay, $closingTime);

                // ignore if location is still open
                if ($this->now < $buiten) {
                    continue;
                }
            }

            assert($buiten >= $registration->getBinnen());
            $this->registratieDao->checkout($registration, $buiten);
        }
    }
}
