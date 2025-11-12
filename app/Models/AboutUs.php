<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AboutUs extends Model
{
    protected $table = 'about_us';

    protected $fillable = [
        'title',
        'description',
        'point_1',
        'point_2',
        'point_3',
        'button_text',
        'main_image_path',
        'ceo_image_path',
        'ceo_name',
        'ceo_title',
    ];
}
