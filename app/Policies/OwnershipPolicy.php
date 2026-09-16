<?php

namespace App\Policies;

use App\Models\User;

class OwnershipPolicy
{

    public function before(User $user, string $ability): ?bool
    {
        if ($user->hasRole('Admin')) {
            return true;
        }

        return null;
    }

    public function view(User $user, $model): bool
    {
        return $this->isOwnedByUser($user, $model);
    }

    public function update(User $user, $model): bool
    {
        return $this->isOwnedByUser($user, $model);
    }

    public function delete(User $user, $model): bool
    {
        return $this->isOwnedByUser($user, $model);
    }

    protected function isOwnedByUser(User $user, $model): bool
    {
        $column = $model->getOwnershipColumn();
        $ownerId = $model->{$column};

        if ($ownerId == $user->id) {
            return true;
        }

        if (! $this->isTeamManager($user)) {
            return false;
        }

        if (empty($ownerId)) {
            return false;
        }

        $teamMember = User::query()->find($ownerId);

        return $teamMember && $teamMember->created_by == $user->id;
    }

    protected function isTeamManager(User $user): bool
    {
        return $user->hasRole('team manager') || $user->hasRole(5);
    }
}
