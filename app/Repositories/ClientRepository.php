<?php

namespace App\Repositories;

use App\Models\Client;
use App\Repositories\Contracts\IClientRepository;

readonly class ClientRepository extends BaseRepository implements IClientRepository
{
    public function __construct(Client $model)
    {
        parent::__construct($model);
    }

}
