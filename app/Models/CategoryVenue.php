<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryVenue extends Model
{
    protected $table = 'category_venues';

    protected $fillable = [
        'category_name'
    ];
}
