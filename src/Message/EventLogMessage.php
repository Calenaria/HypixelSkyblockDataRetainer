<?php

namespace App\Message;

final class EventLogMessage
{
    public const EVENT_STATUS_BUSY = "BUSY";
    public const EVENT_STATUS_FAILED = "FAILED";
    public const EVENT_STATUS_SUCCESS = "SUCCESS";

    private string $eventMessage;
    private string $status;
    private string $eventUuid;
    private ?string $eventName;

    public function __construct(string $eventMessage, string $status, string $eventUuid, string $eventName = null) {
        $this->eventUuid = $eventUuid;
        $this->eventName = $eventName;
        $this->eventMessage = $eventMessage;
        $this->status = $status;
    }

    public function getEventMessage(): string {
        return $this->eventMessage;
    }

    public function getStatus(): string {
        return $this->status;
    }

    public function getEventName(): ?string {
        return $this->eventName;
    }

    public function getEventUuid(): string {
        return $this->eventUuid;
    }
}
