<?php

namespace App\Services;

use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\Support\PathGenerator\PathGenerator;

class CustomPathGenerator implements PathGenerator
{
    public function getPath(Media $media): string
    {
        // Get the media collection name
        $collectionName = $media->collection_name;

        // Your custom logic to generate the path
        $customPath = $collectionName . '/';

        return $customPath;
    }

    public function getPathForConversions(Media $media): string
    {
        return $this->getPath($media) . '/conversions';
    }

    public function getPathForResponsiveImages(Media $media): string
    {
        return $this->getPath($media) . '/responsive';
    }
}
