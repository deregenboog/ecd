<?php

declare(strict_types=1);

namespace Tests\AppBundle\Model;

use AppBundle\Model\TimestampableTrait;
use PHPUnit\Framework\TestCase;

class TimestampableTraitTest extends TestCase
{
    public function testTimestamps()
    {
        $entity = $this->getMockForTrait(TimestampableTrait::class);
        $entity->onPrePersist();
        $this->assertNotNull($entity->getCreated());
        $this->assertEquals($entity->getCreated(), $entity->getModified());

        $entity->onPreUpdate();
        $this->assertGreaterThan($entity->getCreated(), $entity->getModified());
    }
}
