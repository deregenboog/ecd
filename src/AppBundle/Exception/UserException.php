<?php

namespace AppBundle\Exception;

use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Exception that will be shown to the user.
 */
class UserException extends HttpException
{
    public function __construct($code, $message = null)
    {
        // BW compatible...
        if (null == $message) {
            $message = $code;
            $code = 500;
        }
        if (!is_int($code)) {
            $code = 500;
            $message .= ' Errorcode is not int: '.$code;
        }
        parent::__construct($code, $message);
    }
}
