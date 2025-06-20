<?php

namespace App\Services\Logging\Events;

use App\Services\Logging\Contracts\ThirdPartyLoggable;

readonly class ThirdPartyFailureEvent implements ThirdPartyLoggable
{
    public function __construct(
        private string  $service,
        private string  $action,
        private string  $status = 'fail',
        private array   $payload = [],
        private ?string $error = null,
        private array   $tags = [],
    ) {}

    public function getServiceName(): string
    {
        return $this->service;
    }

    public function getAction(): string
    {
        return $this->action;
    }

    public function getPayload(): array
    {
        return $this->payload;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getError(): ?string
    {
        return $this->error;
    }

    public function getTags(): array
    {
        return $this->tags;
    }
}
