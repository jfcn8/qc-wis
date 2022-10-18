<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemLog extends Model
{
    use HasFactory;
    protected $primaryKey = 'item_log_id';
    protected $fillable = [
        'date_request',
        'reference_id',
        'action',
        'quantity',
        'ris_no',
        'user_id',
    ];

    public function Reference()
    {
        return $this->belongsTo(Reference::class, 'reference_id', 'reference_id');
    }

    public function Ris()
    {
        return $this->belongsTo(Ris::class, 'ris_no', 'ris_no');
    }

    protected static $logsAttributes = [
        'date_request',
        'reference_id',
        'action',
        'quantity',
        'ris_no',
    ];
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(
            [
                'date_request',
                'Reference.reference_id',
                'action',
                'quantity',
                'ris_no',
            ])
        ->useLogName('Item Log');
    }
}