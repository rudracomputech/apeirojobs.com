<?php

namespace App\Models\Traits;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

trait HasOwnership
{


    protected static function bootHasOwnership(): void
    {
        static::addGlobalScope('ownership', function (Builder $builder) {

            if (! auth()->check()) {
                return;
            }

            $user = auth()->user();

            if ($user->hasRole('Admin')) {
                return;
            }

            $model = $builder->getModel();
            $ownershipColumn = $model->getTable() . '.' . $model->getOwnershipColumn();

            if ($user->hasRole('team manager') || $user->hasRole(5)) {
                $builder->where(function (Builder $query) use ($ownershipColumn, $user): void {
                    $query->where($ownershipColumn, $user->id)
                        ->orWhereIn($ownershipColumn, function ($subQuery) use ($user): void {
                            $subQuery->select('id')
                                ->from('users')
                                ->where('created_by', $user->id);
                        });
                });

                return;
            }

            $builder->where($ownershipColumn, $user->id);
        });
    }

    public function getOwnershipColumn(): string
    {
        return 'user_id';
    }
}
