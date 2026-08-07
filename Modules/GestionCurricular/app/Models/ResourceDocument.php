<?php

namespace Modules\GestionCurricular\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ResourceDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'resource_request_id', 'document_type', 'document_number',
        'subject', 'filename', 'file_path', 'file_size'
    ];

    public function resourceRequest(): BelongsTo
    {
        return $this->belongsTo(ResourceRequest::class);
    }
}
