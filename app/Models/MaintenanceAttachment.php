<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class MaintenanceAttachment extends Model
{
    use HasFactory;

    protected $table = 'maintenance_attachments';

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'maintenance_log_id',
        'label',
        'path',
        'mime_type',
        'size_bytes',
        'uploaded_by',
    ];

    protected $casts = [
        'size_bytes' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function (self $model) {
            if (! $model->getKey()) {
                $model->{$model->getKeyName()} = (string) Str::ulid();
            }
        });
    }

    public function maintenanceLog(): BelongsTo
    {
        return $this->belongsTo(MaintenanceLog::class);
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
    
    /**
     * Get friendly file size
     */
    public function getFormattedSizeAttribute(): string
    {
        $bytes = $this->size_bytes;
        
        if ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        }
        
        return $bytes . ' bytes';
    }
    
    /**
     * Get file extension
     */
    public function getExtensionAttribute(): string
    {
        return pathinfo($this->path, PATHINFO_EXTENSION);
    }
}
