<?php

namespace App\Contracts;

interface FileUploadRequest
{
    /**
     * Retrieve a file from the request.
     *
     * @param string|null $key
     * @param mixed       $default
     *
     * @return \Illuminate\Http\UploadedFile|\Illuminate\Http\UploadedFile[]|array|null
     */
    public function file($key = null, $default = null);
}
