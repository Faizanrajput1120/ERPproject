<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TRNDTL extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'trn_dtl';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'v_no',
        'v_type',
        'aid',
        'cid',
        'descr',
        'debit',
        'credit',
        'status',
        'prepared_by',
        'cash_acc',
        'pre_bal',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'debit' => 'decimal:2',
        'credit' => 'decimal:2',
        'pre_bal' => 'decimal:2',
    ];

    // Relationships
    public function account()
    {
        return $this->belongsTo(ChartOfAccount::class, 'aid', 'acc_id');
    }

    public function workspace()
    {
        return $this->belongsTo(Workspace::class, 'cid', 'cid');
    }

    public function preparedBy()
    {
        return $this->belongsTo(User::class, 'prepared_by');
    }

    // Scopes
    public function status($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeByVoucher($query, $vNo)
    {
        return $query->where('v_no', $vNo);
    }
}