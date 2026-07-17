<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PackageFeature extends Model
{
    protected $table = 'package_features';
    protected $fillable = [
        'package_id', 'field_label', 'field_value', 'field_type'
    ];

    public function package()
    {
        return $this->belongsTo(Package::class);
    }
}
