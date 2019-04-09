<?php

namespace App\Http\Services\Previews;

use App\Enums\ResourceSubtypes;
use App\Http\Services\Previews\Interfaces\Previewable;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class PreviewFactory
{
    /**
     * @param $subtype
     *
     * @return Previewable
     */
    public static function make($subtype): Previewable
    {
        switch ($subtype) {
            case ResourceSubtypes::YOUTUBE:
                return new YoutubePreview();
                break;
            case ResourceSubtypes::SLIDE:
                return new SlidePreview();
                break;
            case ResourceSubtypes::SURVEY:
                return new SurveyPreview();
                break;
            case ResourceSubtypes::HOTSPOT:
                return new HotspotPreview();
                break;
            default:
                throw new UnprocessableEntityHttpException('The subtype is incorrect');
                break;
        }
    }


}