<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Submission extends Model
{
    /** @use HasFactory<\Database\Factories\SubmissionFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'company_id',
        'category_id',
        'logo',
        'galeries',
        'message',
    ];

    protected $casts = [
        'galeries' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the logo URL
     */
    public function getLogoUrlAttribute(): string
    {
        if (!$this->logo) {
            return '';
        }

        return app(\App\Services\ImageService::class)->url($this->logo);
    }

    /**
     * Get the logo thumbnail URL
     */
    public function getLogoThumbnailUrlAttribute(): string
    {
        if (!$this->logo) {
            return '';
        }

        return app(\App\Services\ImageService::class)->thumbnailUrl($this->logo);
    }

    /**
     * Get galeries URLs
     */
    public function getGaleriesUrlsAttribute(): array
    {
        if (!$this->galeries) {
            return [];
        }

        $imageService = app(\App\Services\ImageService::class);

        return collect($this->galeries)->map(function ($path) use ($imageService) {
            return [
                'path' => $path,
                'url' => $imageService->url($path),
                'thumbnail' => $imageService->thumbnailUrl($path),
            ];
        })->toArray();
    }
}
