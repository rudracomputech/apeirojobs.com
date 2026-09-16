<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class StudentDocument extends Model implements HasMedia
{
    use SoftDeletes, InteractsWithMedia;

    protected $guarded = ['id'];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('document')
            ->singleFile();
    }
}
