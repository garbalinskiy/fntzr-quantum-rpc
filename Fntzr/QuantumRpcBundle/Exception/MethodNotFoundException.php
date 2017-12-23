<?php

namespace Fntzr\QuantumRpcBundle\Exception;

class MethodNotFoundException extends AbstractExtension
{
    protected $code = -32601;
    protected $message = "The method does not exist / is not available.";
}