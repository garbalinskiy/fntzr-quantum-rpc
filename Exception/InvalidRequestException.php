<?php

namespace Fntzr\QuantumRpcBundle\Exception;

class InvalidRequestException extends AbstractExtension
{
    protected $code = -32600;
    protected $message = "The JSON sent is not a valid Request object.";
}