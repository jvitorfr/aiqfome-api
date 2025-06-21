<?php

namespace App\Services;

use App\Models\Client;
use App\Repositories\ClientRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

readonly class ClientService
{
    public function __construct(
        private ClientRepository $repository
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

    public function findByEmail(string $email): ?Client
    {
        return $this->repository
            ->where('email', $email)
            ->first();
    }

    /**
     */
    public function create(array $data): Model|Client
    {
        $data['password'] = bcrypt($data['password']);
        return $this->repository->create($data);
    }

    /**
     */
    public function update(Client $client, array $data): Model|Client
    {
        return $this->repository->update($client, $data);
    }

    public function delete(Client $client): void
    {
        $this->repository->delete($client);
    }
}
