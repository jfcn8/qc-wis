<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Article extends Model
{
    use HasFactory, LogsActivity;
    protected $primaryKey = 'article_id';
    protected $fillable = ['article', 'classification_id'];

    public function Classification()
    {
        return $this->belongsTo(Classification::class, 'classification_id', 'classification_id');
    }

    public function Item()
    {
        return $this->belongsToMany(Item::class, 'article_id', 'article_id');
    }

    protected static $logsAttributes = ['article', 'classification_id'];
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(['article', 'Classification.classification'])
        ->useLogName('Article');
    }


}
