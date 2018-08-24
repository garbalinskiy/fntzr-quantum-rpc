<?php

namespace Fntzr\QuantumRpcBundle\Exception;

class InvalidParamsApiException extends AbstractApiException
{
    protected $message = "Invalid method parameter(s)";
    protected $code = -32602;
}