<?php

namespace HsBundle\Exception;

class InvoiceLockedException extends HsException
{
    protected $message = 'Invoice is locked.';
}
