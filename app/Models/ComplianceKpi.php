<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ComplianceKpi extends Model
{
    use HasFactory;
    protected $fillable = ['franchise_id','period_year','period_month','external_turnover'];
    public function franchise(): BelongsTo { return $this->belongsTo(Franchise::class); }
}
