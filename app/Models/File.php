<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory;

    protected $fillable = [
        'filename',
        'original_filename',
        'file_path',
        'file_size',
        'file_type',
        'category_id',
        'uploaded_by',
        'driver_id',
        'description',
        'tags',
        'is_public'
    ];

    // Relationships
    public function category()
    {
        return $this->belongsTo(FileCategory::class);
    }

    public function uploadedBy()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    public function accessLogs()
    {
        return $this->hasMany(FileAccessLog::class);
    }
}