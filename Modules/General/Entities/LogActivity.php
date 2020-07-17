<?php

namespace Modules\General\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LogActivity extends Model
{
    use SoftDeletes;
    protected $table       = "log_activities";

    const UPDATE           = 1;
    const CANCEL           = 2;
    const DELETE           = 3;

    protected $fillable = [
        'name',
        'action',
        'description',
        'subject_id',
        'subject_model',
        'parent_id',
        'parent_model',
        'properties',
        'issued_by',
        'issue_date',
        'is_active',
    ];
}
