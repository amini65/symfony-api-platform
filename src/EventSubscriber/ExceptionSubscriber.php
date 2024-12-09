<?php

namespace App\EventSubscriber;

use App\Service\ApiResponseFormatter;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class ExceptionSubscriber implements EventSubscriberInterface
{
    private ApiResponseFormatter $responseFormatter;

    public function __construct(ApiResponseFormatter $responseFormatter)
    {
        $this->responseFormatter = $responseFormatter;
    }

    public function onKernelException(ExceptionEvent $event): void
    {
//        $exception = $event->getThrowable();
//        $response = $this->responseFormatter->error(
//            $exception->getMessage(),
//            $exception->getCode() ?: 500
//        );
//
//        $event->setResponse($response);
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => 'onKernelException',
        ];
    }
}
