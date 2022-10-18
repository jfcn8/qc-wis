<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Classification extends Model
{
    use HasFactory, LogsActivity;

    protected $primaryKey = 'classification_id';
    protected $fillable = ['classification'];

    public function article()
    {
        return $this->hasMany(Article::class, 'classification_id', 'article_id');
    }

    protected static $logsAttributes = ['classification'];
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(['classification'])
        ->useLogName('Classification');
    }
}
