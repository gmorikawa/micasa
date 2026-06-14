<?php

namespace App\Core\User;

enum UserRole: string
{
    case ADMIN = 'admin';
    case MEMBER = 'member';
}
