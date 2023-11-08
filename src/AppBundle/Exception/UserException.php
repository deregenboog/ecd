<?php

namespace AppBundle\Exception;

use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Exception that will be shown to the user.
 */
class UserException extends HttpException
{



    public function __construct($code, $message=null)
    {
        //BW compatible...
        if($message==null){
            $message = $code;
            $code = 500;
        }
        parent::__construct($code,$message);
    }
}
