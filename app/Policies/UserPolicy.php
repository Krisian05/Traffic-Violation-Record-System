<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    // Only operators manage users
    public function viewAny(User $user): bool
    {
        return $user->isOperator();
    }

    public function view(User $user, User $model): bool
    {
        return $user->isOperator();
    }

    public function create(User $user): bool
    {
        return $user->isOperator();
    }

    public function update(User $user, User $model): bool
    {
        return $user->isOperator();
    }

    // Operators can delete any user except themselves
    public function delete(User $user, User $model): bool
    {
        return $user->isOperator() && $user->id !== $model->id;
    }

    public function restore(User $user, User $model): bool
    {
        return $user->isOperator();
    }

    public function forceDelete(User $user, User $model): bool
    {
        return $user->isOperator() && $user->id !== $model->id;
    }
}
