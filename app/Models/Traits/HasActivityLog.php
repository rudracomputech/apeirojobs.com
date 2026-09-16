<?php

namespace App\Models\Traits;

if (! trait_exists('Spatie\Activitylog\Traits\LogsActivity') && trait_exists('Spatie\Activitylog\Models\Concerns\LogsActivity')) {
    class_alias('Spatie\Activitylog\Models\Concerns\LogsActivity', 'Spatie\Activitylog\Traits\LogsActivity');
}

if (! class_exists('Spatie\Activitylog\LogOptions') && class_exists('Spatie\Activitylog\Support\LogOptions')) {
    class_alias('Spatie\Activitylog\Support\LogOptions', 'Spatie\Activitylog\LogOptions');
}

use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

trait HasActivityLog
{
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        $options = LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty();

        if (method_exists($options, 'dontSubmitEmptyLogs')) {
            $options->dontSubmitEmptyLogs();
        } elseif (method_exists($options, 'dontLogEmptyChanges')) {
            $options->dontLogEmptyChanges();
        }

        return $options;
    }
}