<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChartOfAccount extends Model
{
    protected $table = "chart_of_accounts";
    protected $primaryKey = 'acc_id';
    public $incrementing = true;
    protected $fillable = ['acc_name', 'level_id'];

    public function level()
    {
        return $this->belongsTo(Level::class, 'level_id');
    }
}
