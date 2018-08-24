<?php

namespace Fntzr\QuantumRpcBundle\Exception;

class MethodNotFoundException extends AbstractApiException
{
    protected $message = "The method does not exist / is not available.";
    protected $code = -32601;
}