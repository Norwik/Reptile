<?php

namespace App\EventSubscriber;

use Symfony\Component\Uid\Uuid;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Exception\SessionNotFoundException;
use Monolog\LogRecord;

/**
 * CorrelationIdSubscriber Class.
 */
class CorrelationIdSubscriber
{
    /** @var RequestStack */
    private $requestStack;

    private const HEADER_NAME = "X-Correlation-ID";

    /**
     * Class Constructor.
     */
    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    /**
     * Appends CorrelationId to log record.
     *
     * @return array
     */
    public function __invoke(LogRecord $record): LogRecord
    {
        $request = $this->requestStack->getCurrentRequest();

        if ($request && !empty($request->headers->get(self::HEADER_NAME))
            && Uuid::isValid($request->headers->get(self::HEADER_NAME))) {
            $record->extra['CorrelationId'] = $request->headers->get(self::HEADER_NAME);

            return $record;
        }

        $correlationId = (string) Uuid::v4();
        $record->extra['CorrelationId'] = $correlationId;
        $request->headers->set(self::HEADER_NAME, $correlationId);

        return $record;
    }
}
