<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Workspace extends Model
{
    use HasFactory;
    protected $table = 'workspace';
    protected $primaryKey = 'cid';
    protected $fillable = ['c_name'];

    // Relationship: Workspace has many users
    public function users()
{
    return $this->hasMany(User::class, 'fk_cid', 'cid')->where('role', 'user');
}

    // Relationship: Workspace has many admins (users with role 'admin')
    public function admins()
    {
        return $this->hasMany(User::class, 'fk_cid', 'cid')->where('role', 'admin');
    }
}
