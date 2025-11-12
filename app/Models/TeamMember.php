<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeamMember extends Model
{
    protected $fillable = [
        'name',
        'designation',
        'bio',
        'image_path',
        'linkedin_url',
        'email',
        'color_class',
    ];
}
