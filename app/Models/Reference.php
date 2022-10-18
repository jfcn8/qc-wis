<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Reference extends Model
{
    use HasFactory, LogsActivity;

    protected $primaryKey = 'reference_id';
    protected $fillable = [
        'reference',
        'stock',
        'price',
        'item_id',
    ];

    public function Item()
    {
        return $this->belongsTo(Item::class, 'item_id', 'item_id');
    }

    public function RefItem()
    {
        return $this->belongsTo(Item::class, 'item_id', 'item_id');
    }

    public function ItemLog()
    {
        return $this->belongsToMany(ItemLog::class, 'reference_id', 'reference_id');
    }

    protected static $logsAttributes = ['reference',
    'stock',
    'price',
    'item_id',];
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(['reference',
        'stock',
        'price',
        'Item.description',])
        ->useLogName('Reference');
    }
}
