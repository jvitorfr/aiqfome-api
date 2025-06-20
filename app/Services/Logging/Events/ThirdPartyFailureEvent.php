<?php

namespace App\Services\Logging\Events;

use App\Services\Logging\Contracts\ThirdPartyLoggable;

class ThirdPartyFailureEvent implements ThirdPartyLoggable
{
    public function __construct(
        private readonly string $service,
        private readonly string $action,
        private readonly string $status = 'fail',
        private readonly array $payload = [],
        private readonly ?string $error = null,
        private readonly array $tags = [],
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
