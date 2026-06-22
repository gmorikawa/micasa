<?php

namespace App\Core\User\Actions;

use App\Core\User\UserRepository;

class SearchUser
{
    private readonly UserRepository $repository;

    public function __construct(
        UserRepository $repository
    ) {
        $this->repository = $repository;
    }
    
    public function execute(): array
    {
        return $this->repository->findAll();
    }
}
