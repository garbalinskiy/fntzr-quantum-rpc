<?php

namespace Fntzr\QuantumRpcBundle\Exception;

class ParseErrorException extends AbstractExtension
{
    protected $code = -32700;
    protected $message = "An error occurred on the server while parsing the JSON text.";
}