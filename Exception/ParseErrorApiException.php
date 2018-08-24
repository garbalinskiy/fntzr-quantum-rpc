<?php

namespace Fntzr\QuantumRpcBundle\Exception;

class ParseErrorApiException extends AbstractApiException
{
    protected $message = "An error occurred on the server while parsing the JSON text.";
    protected $code = -32700;
}