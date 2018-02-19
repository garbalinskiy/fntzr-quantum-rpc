<?php

namespace Fntzr\QuantumRpcBundle\Controller;

use Fntzr\QuantumRpcBundle\Exception\AbstractExtension;
use Fntzr\QuantumRpcBundle\Exception\InternalErrorException;
use Fntzr\QuantumRpcBundle\Exception\MethodNotFoundException;
use Fntzr\QuantumRpcBundle\Exception\ParseErrorException;
use Fntzr\QuantumRpcBundle\Service\AbstractMethodService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class RootController extends Controller
{

    /** @var ContainerInterface */
    var $container;

    /** @var $config */
    var $config;

    /**
     * RootController constructor.
     * @param ContainerInterface $container
     * @param array $config
     */
    public function __construct(ContainerInterface $container, array $config)
    {
        $this->container = $container;
        $this->config = $config;
    }

    public function execute(Request $request, string $version, string $method)
    {
        try {
            $serviceName = $this->getServiceName($version, $method);

            if (!$serviceName) {
                throw new MethodNotFoundException();
            }

            $service = $this->container->get($serviceName);

            if (!$service) {
                throw new InternalErrorException('Service was not found');
            }

            if (!$service instanceof AbstractMethodService) {
                throw new InternalErrorException('Invalid service entity');
            }

            $content = $request->getContent();
            $data = empty($content) ? [] : json_decode($content, true);

            if (!is_array($data)) {
                throw new ParseErrorException();
            }

            $result = $service->execute($data);
            $response = $this->getDataResponse($result);
        } catch (\Exception $exception) {
            $response = $this->getErrorResponse($exception);
        }

        return $response;
    }

    protected function getServiceName(string $version, string $method)
    {
        return $this->config['versions'][$version]['methods'][$method] ?? null;
    }

    protected function getErrorResponse(\Exception $exception)
    {
        $data = [
            'code' => $exception->getCode(),
            'message' => $exception->getMessage()
        ];

        return new JsonResponse(["error" => $data], 200);
    }

    protected function getDataResponse($data)
    {
        return new JsonResponse(["result" => $data], 200);
    }
}
