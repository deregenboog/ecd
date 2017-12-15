<?php

namespace HsBundle\Exception;

class InvoiceNotLockedException extends HsException
{
    protected $message = 'Invoice is not locked.';
}
