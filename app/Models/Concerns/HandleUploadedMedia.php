<?php

namespace App\Models\Concerns;

use Closure;
use ErrorException;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\MediaCollections\FileAdder;
use Symfony\Component\HttpFoundation\File\UploadedFile;

trait HandleUploadedMedia
{
    /**
     * Handle the uploaded Media.
     *
     * @param array   $files
     * @param string  $collectionName
     * @param Closure $addPreference
     *
     * @throws ErrorException
     * @throws \Spatie\MediaLibrary\MediaCollections\Exceptions\DiskDoesNotExist
     * @throws \Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist
     * @throws \Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig
     */
    public function handleUploadedMedia(array $files, string $collectionName, Closure $addPreference = null): void
    {
        if ($addPreference === null) {
            $addPreference = static function (UploadedFile $file, FileAdder $fileAdder) {
                return $fileAdder->usingFileName(md5(Str::uuid()->toString()).'.'.$file->getClientOriginalExtension());
            };
        }

        foreach ($files as $file) {
            if (!($file instanceof UploadedFile)) {
                throw new ErrorException('Argument $files passed to addMedias() must be an array of UploadedFile instances.');
            }

            $fileAdder = $addPreference($file, $this->addMedia($file)->usingFileName(md5(Str::uuid()->toString()).'.'.$file->getClientOriginalExtension()));

            if (!($fileAdder instanceof FileAdder)) {
                throw new ErrorException('The given closure should return FileAdder object.');
            }

            $fileAdder->toMediaCollection($collectionName)
                ->getGeneratedConversions();
        }
    }

    /**
     * Move a file to the media library.
     *
     * @param string|\Symfony\Component\HttpFoundation\File\UploadedFile $file
     *
     * @return \Spatie\MediaLibrary\MediaCollections\FileAdder
     */
    abstract public function addMedia($file): FileAdder;
}
