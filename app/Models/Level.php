<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Level extends Model
{
    protected $primaryKey = 'level_id';
    public $incrementing = true;
    protected $fillable = ['level_title', 'group_id', 'pre_id'];

    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id');
    }

    public function pre()
    {
        return $this->belongsTo(Level::class, 'pre_id');
    }

    public function children()
    {
        return $this->hasMany(Level::class, 'pre_id');
    }

    // NEW: Relationship to accounts
    public function accounts()
    {
        return $this->hasMany(ChartOfAccount::class, 'level_id'); // replace 'level_id' with foreign key in accounts table
    }
}
