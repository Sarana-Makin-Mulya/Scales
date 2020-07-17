<?php

namespace Modules\Auth\Entities;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Modules\HumanResource\Entities\HREmployee;
use Spatie\Activitylog\Traits\LogsActivity;

class User extends Authenticatable
{
    use SoftDeletes;
    use Notifiable;
    use LogsActivity;

    // Account status
    const ACTIVE = 1;
    const BANNED = 2;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'employee_nik',
        'user_group_id',
        'username',
        'email',
        'avatar',
        'password',
        'account_status',
    ];

    protected static $logName = 'User';
    protected static $logFillable = true;
    protected static $logOnlyDirty = true;

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get the url to the user's avatar.
     *
     * @param  string $value
     * @return string
     */
    public function getAvatarAttribute($value)
    {
        if (!$value) {
            return asset('images/user-default.png');
        }

        return asset(Storage::url($value));
    }

    /**
     * Get user emails in lowercase
     *
     * @param  string $avatar
     * @return string
     */
    public function getEmailAttribute($value)
    {
        return strtolower($value);
    }

    /**
     * Relation to table levels
     *
     * @return Modules\Company\Entities\PositionLevel
     */
    public function employee()
    {
        return $this->belongsTo(HREmployee::class, 'employee_nik', 'nik');
    }

    public function group()
    {
        return $this->belongsTo(UserGroup::class, 'user_group_id', 'id');
    }
}
