<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Office extends Model
{
    use HasFactory, LogsActivity;

    protected $primaryKey = 'office_id';
    protected $fillable = ['office'];

    protected static $logsAttributes = ['office'];

    public function Ris()
    {
        return $this->belongsToMany(Ris::class, 'office_id', 'office_id');
    }

    public function User()
    {
        return $this->belongsTo(User::class, 'office_id', 'office_id');
    }
    
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(['office'])
        ->useLogName('Office');
    }

    
}
