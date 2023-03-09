<?php

namespace App\Models;

use App\Contracts\FileUploadRequest;
use App\Models\Concerns\HandleUploadedMedia;
use App\Models\Concerns\OldDateSerializer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Http\UploadedFile;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Arr;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Permission\Traits\HasRoles;

class CmsAdmin extends Authenticatable implements HasMedia
{
    use HasFactory;
    use HandleUploadedMedia;
    use HasRoles;
    use InteractsWithMedia;
    use Notifiable;
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
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the medium profile picture attribute (mutator).
     *
     * @return string|null
     */
    public function getMediumProfilePictureAttribute(): ?string
    {
        $picture = $this->getFirstMediaUrl('profile-picture', 'medium');

        return ($picture === '') ? $picture : asset($picture);
    }

    /**
     * Get the permission list attribute.
     *
     * @return array
     */
    public function getPermissionListAttribute(): array
    {
        return $this->getAllPermissions()->pluck('name')->toArray();
    }

    /**
     * Get role_names attribute value.
     *
     * @return array
     */
    public function getRoleNamesAttribute(): array
    {
        return $this->roles->pluck('name')->toArray();
    }

    /**
     * Get the small profile picture attribute (mutator).
     *
     * @return string|null
     */
    public function getSmallProfilePictureAttribute(): ?string
    {
        $picture = $this->getFirstMediaUrl('profile-picture', 'small');

        return ($picture === '') ? $picture : asset($picture);
    }

    /**
     * Register media collections.
     *
     * @return void
     */
    public function registerMediaCollections(): void
    {
        $collection = $this->addMediaCollection('profile-picture')
            ->singleFile()
            ->acceptsMimeTypes(['image/jpeg', 'image/png']);

        $collection->registerMediaConversions(function (Media $media) {
            $this->addMediaConversion('small')
                ->crop('crop-center', 128, 128)
                ->optimize();

            $this->addMediaConversion('medium')
                ->crop('crop-center', 256, 256)
                ->optimize();

            return $media;
        });
    }

    /**
     * Save the profile picture which being submitted in the given http request.
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
    public function saveProfilePicture(FileUploadRequest $request, string $columnName = 'profile_picture'): self
    {
        if ($request->file($columnName) instanceof UploadedFile) {
            $this->handleUploadedMedia(Arr::wrap($request->file($columnName)), 'profile-picture');
        }

        $this->append(['medium_profile_picture', 'small_profile_picture']);

        return $this;
    }
}
