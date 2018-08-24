<?php

namespace Fntzr\QuantumRpcBundle\Exception;

class AbstractApiException extends \Exception
{
    /** @var array */
    protected $payload = [];

    public function __construct(array $payload = [])
    {
        $this->payload = $payload;
    }

    /**
     * @return array
     */
    public function getPayload()
    {
        return $this->payload;
    }
}