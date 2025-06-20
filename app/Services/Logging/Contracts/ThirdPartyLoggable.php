<?php

namespace App\Services\Logging\Contracts;

interface ThirdPartyLoggable
{
    public function getServiceName(): string;
    public function getAction(): string;
    public function getPayload(): array;
    public function getStatus(): string;
    public function getError(): ?string;
    public function getTags(): array;
}
