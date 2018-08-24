<?php

namespace Fntzr\QuantumRpcBundle\Exception;

class InvalidRequestApiException extends AbstractApiException
{
    protected $message = "The JSON sent is not a valid Request object.";
    protected $code = -32600;
}