<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class HeroSection extends Model
{
    use HasFactory;

    protected $table = 'hero_sections';

    protected $fillable = [
        'heading',
        'highlighted_text',
        'subtext',
        'button1_text',
        'button1_link',
        'button2_text',
        'button2_link',
        'icon_svg',
        'image_path',
        'scroll_target',
    ];
}