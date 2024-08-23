<?php

namespace App\Security;


use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\RouterInterface;

class RedirectExceptionListener
{
    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var RouterInterface $router
     */
    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    /**
     * @var ExceptionEvent $event
     * @return null
     */
    public function onKernelException(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();

        if ($exception instanceof NotFoundHttpException) {
            // Not Found
            $response = new RedirectResponse($this->router->generate('error404'));
        } elseif ($exception instanceof AccessDeniedHttpException) {
            // Forbidden 
            $response = new RedirectResponse($this->router->generate('error403'));
        } elseif ($exception instanceof HttpException) {
            switch ($exception->getStatusCode()) {
                case 400:
                    // Bad Request
                    $response = new RedirectResponse($this->router->generate('error400'));
                    break;
                case 500:
                    // Internal Server Error
                    $response = new RedirectResponse($this->router->generate('error500'));
                    break;
                default:
                    return;
            }
        } else {
            return;
        }

        $event->setResponse($response);
    }
}
