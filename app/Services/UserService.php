<?php

namespace App\Services;

use App\Models\Client;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

readonly class UserService
{
    public function __construct(
        private UserRepository $repository
    ) {
    }

    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return $this->repository->query()
            ->orderBy('name')
            ->paginate($perPage);
    }

    public function find(int $id): Model|User
    {
        return $this->repository->find($id);
    }

    public function findByEmail(string $email): ?User
    {
        return $this->repository
            ->where('email', $email)
            ->first();
    }

    /**
     */
    public function create(array $data): Model|User
    {
        $data['password'] = bcrypt($data['password']);
        return $this->repository->create($data);
    }

    /**
     */
    public function update(Client $client, array $data): Model|User
    {
        return $this->repository->update($client, $data);
    }

    public function delete(Client $client): void
    {
        $this->repository->delete($client);
    }
}
