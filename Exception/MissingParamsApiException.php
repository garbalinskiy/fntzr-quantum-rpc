<?php

namespace Fntzr\QuantumRpcBundle\Exception;

class MissingParamsApiException extends AbstractApiException
{
    protected $message = "Missing parameter(s)";
    protected $code = -32602;
}