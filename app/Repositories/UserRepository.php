<?php

namespace App\Repositories;

use App\Models\User;

readonly class UserRepository extends BaseRepository
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }


}
