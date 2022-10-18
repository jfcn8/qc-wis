<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class DBM extends Model
{
    use HasFactory, LogsActivity;
    protected $fillable = [
        'filename',
        'remarks',
    ];

    protected static $logsAttributes = ['filename', 'remarks'];
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(['filename', 'remarks'])
        ->useLogName('DBM Price');
    }
}
