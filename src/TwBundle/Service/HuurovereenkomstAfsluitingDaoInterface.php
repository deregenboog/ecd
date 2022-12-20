<?php

namespace TwBundle\Service;

interface HuurovereenkomstAfsluitingDaoInterface extends AfsluitingDaoInterface
{
    public function countByProject(\DateTime $start, \DateTime $end);
}
