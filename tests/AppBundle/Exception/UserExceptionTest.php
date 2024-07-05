<?php

declare(strict_types=1);

namespace Tests\AppBundle\Exception;

use AppBundle\Exception\UserException;
use PHPUnit\Framework\TestCase;

class UserExceptionTest extends TestCase
{
    /**
     * @dataProvider dataProvider
     */
    public function testUserException(array $args, $expectedCode, $expectedMessage)
    {
        $exception = new UserException(...$args);
        $this->assertEquals($expectedCode, $exception->getStatusCode());
        $this->assertEquals($expectedMessage, $exception->getMessage());
    }

    public function dataProvider()
    {
        return [
            [
                ['something is wrong'],
                500,
                'something is wrong',
            ],
            [
                [400, 'something is wrong'],
                400,
                'something is wrong',
            ],
            [
                ['500', 'something is wrong'],
                500,
                'something is wrong Errorcode is not int: 500',
            ],
            [
                ['nan', 'something is wrong'],
                500,
                'something is wrong Errorcode is not int: 500', // shouldn't this be 'something is wrong Errorcode is not int: nan'?
            ],
        ];
    }
}
