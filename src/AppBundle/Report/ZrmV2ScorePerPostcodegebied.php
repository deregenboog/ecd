<?php

namespace AppBundle\Report;

use AppBundle\Entity\ZrmV2;

class ZrmV2ScorePerPostcodegebied extends ZrmScorePerPostcodegebied
{
    protected $title = 'ZRM(v2)-score per postcodegbied';

    protected $class = ZrmV2::class;
}
