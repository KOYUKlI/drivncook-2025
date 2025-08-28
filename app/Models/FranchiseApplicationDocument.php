<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FranchiseApplicationDocument extends Model
{
    use HasFactory;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [ 'id', 'franchise_application_id', 'kind', 'path' ];

    public function application(): BelongsTo { return $this->belongsTo(FranchiseApplication::class, 'franchise_application_id'); }
}
