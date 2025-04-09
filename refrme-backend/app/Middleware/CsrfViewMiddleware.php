<?php

namespace App\Middleware;

use Cake\Core\ContainerInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Server\MiddlewareInterface;

class CsrfViewMiddleware implements MiddlewareInterface
{
    protected ContainerInterface $container;
    private ResponseFactoryInterface $responseFactory;

    public function __construct($container, ResponseFactoryInterface $responseFactory)
    {
        $this->container = $container;
        $this->responseFactory = $responseFactory;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $csrf = $this->container->csrf;
        $view = $this->container->view;

        $view->getEnvironment()->addGlobal(
            'csrf',
            [
                'field' => sprintf(
                '<input type="hidden" name="%s" value="%s">' .
                '<input type="hidden" name="%s" value="%s">',
                $csrf->getTokenNameKey(),
                $csrf->getTokenName(),
                $csrf->getTokenValueKey(),
                $csrf->getTokenValue()),
            ]
        );

        return $handler->handle($request);
    }
}
