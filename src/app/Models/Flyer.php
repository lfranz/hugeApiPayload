<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Flyer extends Model
{

    protected $table = 'flyers';
    protected $fillable = [
        'id',
        'title',
        'url',
        'start_date',
        'end_date',
        'flyer_url',
        'flyer_files'
    ];
}
