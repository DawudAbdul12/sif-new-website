<?php

namespace App\Models;

use App\Models\Concerns\RecordsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SiteSetting extends Model
{
    use RecordsActivity, SoftDeletes;

    protected $fillable = [
        'key',
        'value',
        'group',
        'label',
    ];
}
