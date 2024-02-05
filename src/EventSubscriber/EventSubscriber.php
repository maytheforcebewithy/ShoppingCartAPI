<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\KernelEvents;

class ExceptionSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::EXCEPTION => 'onKernelException',
        ];
    }

    public function onKernelException(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();

        switch (true) {
            case $exception instanceof NotFoundHttpException:
                $response = new JsonResponse(['message' => $exception->getMessage()], Response::HTTP_NOT_FOUND);
                break;

            case $exception instanceof BadRequestHttpException:
                $response = new JsonResponse(['message' => $exception->getMessage()], Response::HTTP_BAD_REQUEST);
                break;

            default:
                $response = new JsonResponse(['message' => 'Internal server error'], Response::HTTP_INTERNAL_SERVER_ERROR);
                break;
        }

        $event->setResponse($response);
    }
}
