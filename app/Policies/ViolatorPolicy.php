<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Violator;
use Illuminate\Auth\Access\Response;

class ViolatorPolicy
{
    // Any authenticated user can list and view motorists
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Violator $violator): bool
    {
        return true;
    }

    // Both operators and traffic officers can register motorists
    public function create(User $user): bool
    {
        return true;
    }

    // Both roles can update — operators cover all, officers can update any motorist
    // (Violators are shared data, no single ownership)
    public function update(User $user, Violator $violator): bool
    {
        return true;
    }

    // Only operators can soft-delete motorists
    public function delete(User $user, Violator $violator): bool
    {
        return $user->isOperator();
    }

    public function restore(User $user, Violator $violator): bool
    {
        return $user->isOperator();
    }

    public function forceDelete(User $user, Violator $violator): bool
    {
        return $user->isOperator();
    }
}
