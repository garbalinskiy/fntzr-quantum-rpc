<?php

namespace Fntzr\QuantumRpcBundle\Exception;

use Throwable;

class MissingParamsException extends AbstractExtension
{
    protected $code = -32602;

    public function __construct(array $paramters = [])
    {
        parent::__construct("Missing parameter(s): " . implode($paramters, " "));
    }
}