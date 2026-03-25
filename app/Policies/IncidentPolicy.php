<?php

namespace App\Policies;

use App\Models\Incident;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class IncidentPolicy
{
    // Any authenticated user can list and view incidents
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Incident $incident): bool
    {
        return true;
    }

    // Both roles can record incidents
    public function create(User $user): bool
    {
        return true;
    }

    // Operators can update any incident.
    // Traffic officers can only update incidents they personally recorded.
    public function update(User $user, Incident $incident): bool
    {
        if ($user->isOperator()) {
            return true;
        }

        return $user->isTrafficOfficer() && $incident->recorded_by === $user->id;
    }

    // Only operators can delete incidents or their media
    public function delete(User $user, Incident $incident): bool
    {
        return $user->isOperator();
    }

    public function deleteMedia(User $user, Incident $incident): bool
    {
        return $user->isOperator();
    }

    public function restore(User $user, Incident $incident): bool
    {
        return $user->isOperator();
    }

    public function forceDelete(User $user, Incident $incident): bool
    {
        return $user->isOperator();
    }
}
