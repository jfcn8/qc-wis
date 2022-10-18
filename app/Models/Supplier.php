<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Supplier extends Model
{
    use HasFactory, LogsActivity;

   
    protected $primaryKey = 'supplier_id';

    protected $fillable = ['supplier','other_information'];

    public function Delivery()
    {
        return $this->belongsToMany(Delivery::class, 'supplier_id', 'supplier_id');
    }

    protected static $logsAttributes = ['supplier', 'other_information'];
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(['supplier','other_information'])
        ->useLogName('Supplier');
    }

}
