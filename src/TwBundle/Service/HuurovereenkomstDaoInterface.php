<?php

namespace TwBundle\Service;

use TwBundle\Entity\Huurovereenkomst;

interface HuurovereenkomstDaoInterface
{
    public function create(Huurovereenkomst $entity);

    public function update(Huurovereenkomst $entity);

    public function delete(Huurovereenkomst $entity);

    public function countByVorm(\DateTime $startdate, \DateTime $enddate);

    public function countByWoningbouwcorporatie(\DateTime $startdate, \DateTime $enddate);

    public function countByAfsluitreden(\DateTime $startdate, \DateTime $enddate);

    public function countByStadsdeel(\DateTime $startdate, \DateTime $enddate);

}
