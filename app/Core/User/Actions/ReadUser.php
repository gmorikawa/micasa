<?php

namespace App\Core\User\Actions;

use App\Core\User\User;
use App\Core\User\UserID;
use App\Core\User\UserRepository;

class ReadUser
{
    private readonly UserRepository $repository;

    public function __construct(
        UserRepository $repository
    ) {
        $this->repository = $repository;
    }
    
    public function execute(UserID $id): ?User
    {
        return $this->repository->findById($id);
    }
}
