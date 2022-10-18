<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Unit extends Model
{
    use HasFactory, LogsActivity;
    protected $primaryKey = 'unit_id';
    protected $fillable = ['unit', 'mnemonic'];


    public function Item()
    {
        return $this->belongsToMany(Item::class, 'unit_id', 'unit_id');
    }

    protected static $logsAttributes = ['mnemonic','unit'];
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(['mnemonic','unit'])
        ->useLogName('Unit');
    }
}
