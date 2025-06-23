<?php

namespace App\Services;

use App\Enums\AuditAction;
use App\Events\AuditLogEvent;
use App\Models\Client;
use App\Repositories\Contracts\IClientRepository;
use App\Services\Logging\AuditService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

readonly class ClientService
{
    public function __construct(
        private IClientRepository $repository,
        protected AuditService    $audit,
    ) {
    }

    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return $this->repository->query()
            ->orderBy('name')
            ->paginate($perPage);
    }

    public function find(int $id): ?Client
    {
        return $this->repository
            ->with('favorites')
            ->find($id);
    }

    /**
     */
    public function create(array $data): Model|Client
    {
        $data['password'] = bcrypt($data['password']);

        /** @var Client $client */
        $client = $this->repository->create($data);

        event(new AuditLogEvent(
            action: AuditAction::CREATED_CLIENT,
            target: $client,
            after: $client->toArray()
        ));

        return $client;
    }

    /**
     */
    public function update(Client $client, array $data): Model|Client
    {
        $beforeClient = $client->toArray();
        $client = $this->repository->update($client, $data);
        event(new AuditLogEvent(
            action: AuditAction::EDITED_CLIENT,
            target: $client,
            before: $beforeClient,
            after: $client->toArray()
        ));

        return $client;
    }

    public function delete(Client $client): void
    {
        $this->repository->delete($client);
        event(new AuditLogEvent(
            action: AuditAction::DELETED_CLIENT,
            target: $client,
            before: $client->toArray(),
            after: $client->toArray()
        ));
    }
}
