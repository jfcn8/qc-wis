<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Activitylog\LogOptions;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, LogsActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    
    protected $fillable = [
        'name',
        'email',
        'password',
        'position',
        'office_id',
        'permissions',
        'access',
        'isActive'
    ];

    // public function getActivitylogOptions(): LogOptions
    // {
    //     return LogOptions::defaults()
    //     ->logOnly(['name', 'email', 'password']);
    //     // Chain fluent methods for configuration options
    // }

    // protected $logAttributes = ['name', 'email', 'password'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];


    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function Activity()
    {
        return $this->hasMany(Activity::class, 'id', 'causer_id');
    }

    public function Delivery()
    {
        return $this->belongsToMany(Delivery::class, 'id', 'user_id');
    }

    public function Office() {
        return $this->belongsTo(Office::class, 'office_id', 'office_id');
    }

    protected static $logsAttributes = ['name', 'email', 'position', 'office_id', 'permissions', 'access', 'isActive'];
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(['name', 'email', 'position', 'permissions', 'access','Office.office', 'isActive'])
        ->logOnlyDirty()
        ->useLogName('Account');
    }
}