<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryVenue extends Model
{
    protected $table = 'category_venues';

    protected $fillable = [
        'category_name',
        'ceremonies',
        'sub_categories',
        'html_file'
    ];

    protected $casts = [
        'ceremonies'     => 'array',
        'sub_categories' => 'array',
        'html_file'      => 'array',
    ];
}