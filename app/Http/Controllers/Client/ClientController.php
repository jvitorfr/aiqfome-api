<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\BaseController;
use App\Http\Requests\StoreClientRequest;
use App\Http\Requests\UpdateClientRequest;
use App\Models\Client;
use App\Services\ClientService;
use Illuminate\Http\JsonResponse;

class ClientController extends BaseController
{
    public function __construct(
        private readonly ClientService $service
    ) {}

    public function index(): JsonResponse
    {
        $clients = $this->service->paginate();
        return $this->respondSuccess($clients);
    }

    public function show(Client $client): JsonResponse
    {
        return $this->respondSuccess($client);
    }

    public function store(StoreClientRequest $request): JsonResponse
    {
        $client = $this->service->create($request->validated());
        return $this->respondCreated($client);
    }

    public function update(UpdateClientRequest $request, Client $client): JsonResponse
    {
        $updated = $this->service->update($client, $request->validated());
        return $this->respondSuccess($updated);
    }

    public function destroy(Client $client): JsonResponse
    {
        $this->service->delete($client);
        return $this->respondMessage('Client deleted');
    }
}
