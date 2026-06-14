<?php

namespace App\Models;

use App\Core\Auth\HashedPassword;
use App\Core\User\Email;
use App\Core\User\User;
use App\Core\User\UserID;
use App\Core\User\UserRole;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class UserModel extends Model
{
    use HasUuids;

    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = true;

    protected $fillable = [
        'id',
        'email',
        'password',
        'role',
    ];

    public function toEntity(): User
    {
        return new User(
            id: new UserID($this->id),
            email: new Email($this->email),
            password: new HashedPassword($this->password),
            role: UserRole::from($this->role)
        );
    }
}
