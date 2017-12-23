<?php

namespace Fntzr\QuantumRpcBundle\Exception;

class InternalErrorException extends AbstractExtension
{
    protected $code = -32603;
    protected $message = "Internal JSON-RPC error.";
}