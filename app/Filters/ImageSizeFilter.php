<?php

namespace App\Filters;

use Intervention\Image\Image;
use Intervention\Image\Filters\FilterInterface;
use Request;

class ImageSizeFilter implements FilterInterface
{
    public function applyFilter(Image $image)
    {
        $width = 100;
        $height = 100;

        if (Request::has('width') && Request::get('width') != 0 && Request::has('height') && Request::get('height') != 0)
            return $image->crop(Request::get('width'), Request::get('height'));

        if (Request::has('width') && Request::get('width') != 0 && !Request::has('height'))
            return $image->crop(Request::get('width'), null, function ($constraint) {
                $constraint->aspectRatio();
            });

        if (Request::has('height') && Request::get('height') != 0 && !Request::has('width'))
            return $image->crop(null, Request::get('height'), function ($constraint) {
                $constraint->aspectRatio();
            });

        return $image->crop($width, $height);
    }
}
