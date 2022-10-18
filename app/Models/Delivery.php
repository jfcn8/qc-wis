<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Delivery extends Model
{
    use HasFactory, LogsActivity;

    protected $primaryKey = 'delivery_id';
    protected $fillable = [
        'delivery_date',
        'stock',
        'supplier_id',
        'reference_id',
        'item_id',
        'user_id',
    ];

    public function Item()
    {
        return $this->belongsTo(Item::class, 'item_id', 'item_id');
    }

    public function Reference()
    {
        return $this->belongsTo(Reference::class, 'reference_id', 'reference_id');
    }

    public function Supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id', 'supplier_id');
    }

    public function User()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    protected static $logsAttributes = [
        'delivery_date',
        'stock',
        'supplier_id',
        'reference_id',
        'item_id',
    ];
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(
            [
                'delivery_date',
                'stock',
                'Supplier.supplier',
                'Reference.reference',
                'Item.description',
            ])
        ->useLogName('Delivery');
    }
}
