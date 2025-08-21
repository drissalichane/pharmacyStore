<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmergencyInfoImage extends Model
{
    protected $fillable = [
        'image_path',
        'title',
        'description',
    ];
}
