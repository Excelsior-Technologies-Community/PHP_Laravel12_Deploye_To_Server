<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Deployment extends Model
{
    protected $fillable = [
        'project_name',
        'server_name',
        'environment',
        'status',
        'duration_seconds',
        'commit_hash',
        'deployed_by',
        'deployment_date',
        'remarks',
    ];

    protected $casts = [
        'deployment_date' => 'datetime',
        'duration_seconds' => 'integer',
    ];
}
