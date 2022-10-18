<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TempRis extends Model
{
    use HasFactory;

    protected $primaryKey = 'temp_ris_id';
    protected $fillable = ['session_id', 'date_request','purpose','item_id','action','quantity','office_id','user_id'];

    public function Article()
    {
        return $this->belongsTo(Article::class, 'article_id', 'article_id');
    }

    public function Item()
    {
        return $this->belongsTo(Item::class, 'item_id', 'item_id');
    }

    public function Reference()
    {
        return $this->hasMany(Reference::class, 'item_id', 'item_id');
    }

    public function Unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id', 'unit_id');
    }
}
