<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Signatory extends Model
{
    use HasFactory, LogsActivity;

    protected $primaryKey = 'signatory_id';

    protected $fillable = ['name','designation', 'mism_certified', 'mism_approved', 'ssmi_noting', 'ssmi_certifying', 'ssmi_approving'];

    protected static $logsAttributes = ['name','designation', 'mism_certified', 'mism_approved', 'ssmi_noting', 'ssmi_certifying', 'ssmi_approving'];
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(['name','designation', 'mism_certified', 'mism_approved', 'ssmi_noting', 'ssmi_certifying', 'ssmi_approving'])
        ->useLogName('Signatory');
    }
}
