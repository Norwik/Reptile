<?php

namespace App\EventSubscriber;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ExceptionSubscriber implements EventSubscriberInterface
{
    /** @var LoggerInterface */
    private $logger;

    /**
     * Class Constructor.
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        if ($event->getThrowable() instanceof NotFoundHttpException) {
            $event->setResponse(new JsonResponse([
                'errorMessage' => "Route not found",
                'correlationId' => $event->getRequest()->headers->get('X-Correlation-ID', ''),
            ], Response::HTTP_NOT_FOUND));
        } else {
            $this->logger->error(sprintf(
                'Exception with errorMessage [%s] thrown: %s',
                $event->getThrowable()->getMessage(),
                $event->getThrowable()->getTraceAsString()
            ));

            $event->setResponse(new JsonResponse([
                'errorMessage' => $event->getThrowable()->getMessage(),
                'correlationId' => $event->getRequest()->headers->get('X-Correlation-ID', ''),
            ], Response::HTTP_INTERNAL_SERVER_ERROR));
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => 'onKernelException',
        ];
    }
}
