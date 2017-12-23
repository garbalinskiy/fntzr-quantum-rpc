<?php

namespace Fntzr\QuantumRpcBundle\Exception;

class InvalidParamsException extends AbstractExtension
{
    protected $code = -32602;
    protected $message = "Invalid method parameter(s).";
}