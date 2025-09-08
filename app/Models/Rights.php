<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rights extends Model
{
    use HasFactory;

    protected $fillable = [
        'app_name',
        'write',
        'edit',
        'erase',
        'read',
        'fk_userid',
    ];

    // Relation: A Right belongs to a User
    public function user()
    {
        return $this->belongsTo(User::class, 'fk_userid');
    }
}
