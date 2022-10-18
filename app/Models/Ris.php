<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Ris extends Model
{
    use HasFactory, LogsActivity;
    protected $primaryKey = 'ris_id';
    protected $fillable = ['ris_no', 'date_request','purpose', 'user_id', 'office_id', 'gso', 'budget'];


    public function Office()
    {
        return $this->belongsTo(Office::class, 'office_id', 'office_id');
    }

    public function Itemlog()
    {
        return $this->belongsToMany(ItemLog::class, 'ris_no', 'ris_no');
    }

    public function User()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    protected static $logsAttributes = ['date_request','purpose', 'user_id', 'office_id', 'gso', 'budget'];
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(['date_request','purpose', 'User.name', 'Office.office', 'gso', 'budget'])
        ->useLogName('RIS');
    }
}
