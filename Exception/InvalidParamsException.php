<?php

namespace Fntzr\QuantumRpcBundle\Exception;

use Throwable;

class InvalidParamsException extends AbstractExtension
{
    protected $code = -32602;

    public function __construct(array $paramters = [])
    {
        parent::__construct("Invalid method parameter(s): " . implode($paramters, " "));
    }
}