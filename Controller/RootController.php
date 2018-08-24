<?php

namespace Fntzr\QuantumRpcBundle\Controller;

use Fntzr\QuantumRpcBundle\Exception\AbstractApiException;
use Fntzr\QuantumRpcBundle\Exception\InternalErrorApiException;
use Fntzr\QuantumRpcBundle\Exception\MissingParamsApiException;
use Fntzr\QuantumRpcBundle\Exception\InvalidParamsApiException;
use Fntzr\QuantumRpcBundle\Exception\MethodNotFoundException;
use Fntzr\QuantumRpcBundle\Exception\ParseErrorApiException;
use Fntzr\QuantumRpcBundle\Service\AbstractMethodService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;

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

    protected function getServiceName(string $version, string $method)
    {
        return $this->config['versions'][$version]['methods'][$method] ?? null;
    }

    public function apiAction(Request $request, string $version, string $method)
    {
        try {
            $serviceName = $this->getServiceName($version, $method);

            if (!$serviceName) {
                throw new MethodNotFoundException();
            }

            $service = $this->container->get($serviceName);

            if (!$service) {
                throw new InternalErrorApiException('Service was not found');
            }

            if (!$service instanceof AbstractMethodService) {
                throw new InternalErrorApiException('Invalid service entity');
            }

            if (!is_callable($serviceName, AbstractMethodService::EXECUTE_METHOD)) {
                throw new InternalErrorApiException("Invalid method declaration");
            }

            $content = $request->getContent();
            $data = empty($content) ? [] : json_decode($content, true);

            if (!is_array($data)) {
                throw new ParseErrorApiException();
            }

            $result = $this->executeMethod($service, $data);
            $response = $this->getDataResponse($result);
        } catch (\Exception $exception) {
            $response = $this->getErrorResponse($exception);
        }

        return $response;
    }

    protected function executeMethod(AbstractMethodService $service, $requestParams)
    {
        $methodReflection = new \ReflectionMethod($service, AbstractMethodService::EXECUTE_METHOD);

        $params = [];
        $missingParams = [];

        /* @var \ReflectionParameter $paramReflection */
        foreach ($methodReflection->getParameters() as $paramReflection) {
            $parameterName = $paramReflection->getName();
            $parameterClass = $paramReflection->getClass() ? $paramReflection->getClass()->getName() : null;
            $parameterIsOptional = $paramReflection->isOptional();

            if ($parameterClass && isset(class_implements($parameterClass)[UserInterface::class])) {
                $params[] = $this->getUser();
            } else if (!isset($requestParams[$parameterName]) && !$parameterIsOptional) {
                $missingParams[] = $parameterName;
            } else {
                $params[] = $requestParams[$parameterName] ?? null;
            }
        }

        if (count($missingParams) > 0) {
            throw new MissingParamsApiException($missingParams);
        }

        return call_user_func_array([$service, AbstractMethodService::EXECUTE_METHOD], $params);
    }

    protected function getErrorResponse(\Exception $exception)
    {
        $data = [
            'code' => $exception->getCode(),
            'message' => $exception->getMessage()
        ];

        if ($exception instanceof AbstractApiException && count($exception->getPayload()) > 0) {
            $data["payload"] = $exception->getPayload();
        }

        return new JsonResponse(["error" => $data], 200);
    }

    protected function getDataResponse($data)
    {
        return new JsonResponse(["result" => $data], 200);
    }
}
