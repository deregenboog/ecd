<?php

namespace Tests\AppBundle\PHPUnit;

use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class DoctrineTestCase extends TestCase
{
    /**
     * Asserts that two variables are equal, ignoring differences in whitespace.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     */
    public static function assertEqualsIgnoringWhitespace(string $expected, string $actual, string $message = ''): void
    {
        $constraint = new IsEqualIgnoringWhitespace($expected);

        static::assertThat($actual, $constraint, $message);
    }

    public static function equalToIgnoringWhitespace($value): IsEqualIgnoringWhitespace
    {
        return new IsEqualIgnoringWhitespace($value);
    }

    protected function getEntityManagerMock(): EntityManager
    {
        $em = $this->getMockBuilder(EntityManager::class)
            ->disableOriginalConstructor()
            ->setMethodsExcept(['createQueryBuilder', 'getExpressionBuilder'])
            ->getMock();

        $em->method('getConfiguration')->willReturn(new Configuration());

        return $em;
    }

    /**
     * Expects that the given DQL is generated, ignoring differences in whitespace.
     *
     * @param EntityManagerInterface&MockObject $em
     */
    protected function expectDQL($em, string $expectedDQL)
    {
        $query = new class($em) extends AbstractQuery {
            public function getOneOrNullResult($hydrationMode = null)
            {
            }

            public function getResult($hydrationMode = null)
            {
            }

            public function getSingleResult($hydrationMode = null)
            {
            }

            public function getSQL()
            {
            }

            public function setFirstResult()
            {
                return $this;
            }

            public function setMaxResults()
            {
                return $this;
            }

            protected function _doExecute()
            {
            }
        };

        $em->expects($this->once())
            ->method('createQuery')
            ->with($this->equalToIgnoringWhitespace($expectedDQL))
            ->willReturn($query);

        return $em;
    }
}
