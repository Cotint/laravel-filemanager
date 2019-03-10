<?php

namespace Unisharp\Laravelfilemanager\models;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{

    protected $fillable = [
        'title',
        'description',
        'alt',
        'name',
        'mime_type',
        'size'
    ];
}
