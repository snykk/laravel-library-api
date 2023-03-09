<?php

namespace App\Models;

use App\Contracts\FileUploadRequest;
use App\Models\Concerns\HandleUploadedMedia;
use App\Models\Concerns\OldDateSerializer;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class SeoMeta extends Model implements HasMedia
{
    use HasFactory;
    use HandleUploadedMedia;
    use InteractsWithMedia;
    use OldDateSerializer;
    use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var string[]
     */
    protected $dates = [
        'deleted_at',
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'seo_url',
        'model',
        'foreign_key',
        'locale',
        'seo_title',
        'seo_description',
        'open_graph_type',
    ];

    /**
     * Get the medium profile picture attribute (mutator).
     *
     * @return string|null
     */
    public function getLargeSeoImageAttribute(): ?string
    {
        $image = $this->getFirstMediaUrl('seo-image', 'large');

        return ($image === '') ? $image : asset($image);
    }

    /**
     * Get the small seo image attribute (mutator).
     *
     * @return string|null
     */
    public function getSmallSeoImageAttribute(): ?string
    {
        $image = $this->getFirstMediaUrl('seo-image', 'small');

        return ($image === '') ? $image : asset($image);
    }

    /**
     * Register media collections.
     *
     * @return void
     */
    public function registerMediaCollections(): void
    {
        $collection = $this->addMediaCollection('seo-image')
            ->singleFile()
            ->acceptsMimeTypes(['image/jpeg', 'image/png']);

        $collection->registerMediaConversions(function (Media $media) {
            $this->addMediaConversion('large')
                ->crop('crop-center', 1200, 630)
                ->optimize();

            $this->addMediaConversion('small')
                ->crop('crop-center', 240, 126)
                ->optimize();

            return $media;
        });
    }

    /**
     * Save the seo image which being submitted in the given http request.
     *
     * @param FileUploadRequest $request
     * @param string            $columnName
     *
     * @throws \ErrorException
     * @throws \Spatie\MediaLibrary\MediaCollections\Exceptions\DiskDoesNotExist
     * @throws \Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist
     * @throws \Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig
     *
     * @return $this
     */
    public function saveSeoImage(FileUploadRequest $request, string $columnName = 'seo_image'): self
    {
        if ($request->file($columnName) instanceof UploadedFile) {
            $this->handleUploadedMedia(Arr::wrap($request->file($columnName)), 'seo-image');
        }

        $this->append(['large_seo_image', 'small_seo_image']);

        return $this;
    }

    /**
     * Query scope to get url based SEO metas only.
     *
     * @param Builder $query
     *
     * @return Builder
     */
    public function scopeUrlBasedOnly(Builder $query): Builder
    {
        return $query->whereNull(['model', 'foreign_key']);
    }
}
