<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Models\Activity as ModelsActivity;

class Activity extends Model
{
    use HasFactory;

    protected $primaryKey = 'activity_id';
    protected $fillable = ['action_id', 'description', 'user_id'];

    public function User()
    {
        return $this->belongsTo(User::class, 'causer_id', 'id');
    }

    public function Activity()
    {
        return $this->belongsToMany(ModelsActivity::class, 'causer_id', 'id');
    }

    public function Action()
    {
        return $this->belongsTo(Action::class, 'action_id', 'action_id');
    }
}
