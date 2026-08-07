<?php

namespace Modules\GestionCurricular\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ResourceAttachment extends Model
{
    use HasFactory;

    protected $fillable = ['resource_request_id', 'filename', 'file_path', 'file_size', 'description'];

    public function resourceRequest(): BelongsTo
    {
        return $this->belongsTo(ResourceRequest::class);
    }
}
