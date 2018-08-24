<?php

namespace Fntzr\QuantumRpcBundle\Exception;

class InternalErrorApiException extends AbstractApiException
{
    protected $message = "Internal JSON-RPC error.";
    protected $code = -32603;
}