<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Item extends Model
{
    use HasFactory, LogsActivity;
    protected $table = 'items';
    protected $primaryKey = 'item_id';
    protected $fillable = [
        'description',
        'stock_number',
        'article_id',
        'unit_id',
    ];

    public function Article()
    {
        return $this->belongsTo(Article::class, 'article_id', 'article_id');
    }

    public function TempRis()
    {
        return $this->belongsTo(TempRis::class, 'item_id', 'item_id');
    }

    public function Ris()
    {
        return $this->belongsTo(Ris::class, 'ris_no', 'ris_no');
    }

    public function Reference()
    {
        return $this->hasMany(Reference::class, 'item_id', 'item_id');
    }

    public function Unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id', 'unit_id');
    }

    public function Delivery()
    {
        return $this->hasMany(Delivery::class, 'item_id', 'item_id');
    }

    protected static $logsAttributes = [
        'description',
        'stock_number',
        'article_id',
        'unit_id',];
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly([
            'description',
            'stock_number',
            'Article.article',
            'Unit.unit',])
        ->useLogName('Item');
    }

}
