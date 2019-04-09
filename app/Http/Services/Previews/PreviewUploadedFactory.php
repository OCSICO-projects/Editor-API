<?php

namespace App\Http\Services\Previews;

use App\Enums\ResourceSubtypes;
use App\Http\Services\Previews\Interfaces\PreviewableUploaded;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class PreviewUploadedFactory
{
    /**
     * @param $subtype
     *
     * @return PreviewableUploaded
     */
    public static function make($subtype): PreviewableUploaded
    {
        switch ($subtype) {
            case ResourceSubtypes::VIDEO:
                return new VideoPreview();
                break;
            case ResourceSubtypes::IMAGE:
                return new ImagePreview();
                break;
            default:
                throw new UnprocessableEntityHttpException('The subtype is incorrect');
                break;
        }
    }


}
