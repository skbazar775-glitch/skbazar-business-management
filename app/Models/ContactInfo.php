<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactInfo extends Model
{
    protected $table = 'contact_infos';

    protected $fillable = [
        'office_title',
        'office_address',
        'phone_title',
        'phone_1',
        'phone_2',
        'email_title',
        'email_1',
        'email_2',
        'hours_title',
        'weekdays_hours',
    ];
}
