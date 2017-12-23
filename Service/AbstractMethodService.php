<?php

namespace Fntzr\QuantumRpcBundle\Service;

abstract class AbstractMethodService
{
    abstract public function execute(array $params = []);
}