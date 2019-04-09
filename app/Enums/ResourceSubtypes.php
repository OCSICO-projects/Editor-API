<?php

namespace App\Enums;

class ResourceSubtypes
{
    CONST IMAGE = 'image';
    CONST PDF = 'pdf';
    CONST VIDEO = 'video';
    CONST YOUTUBE = 'youtube';

    CONST SLIDE = 'slide';
    CONST HOTSPOT = 'hotspot';
    CONST SURVEY = 'survey';

    /**
     * @return array
     */
    static public function getAll() : array
    {
        $class = new \ReflectionClass(__CLASS__);

        return $class->getConstants();
    }

    /**
     * @return array
     */
    static public function getAllNotUploadable() : array
    {
        return [
            self::YOUTUBE,
            self::SLIDE,
            self::HOTSPOT,
            self::SURVEY,
        ];
    }

    /**
     * @return array
     */
    static public function getAllNotUploadableAndRenderable() : array
    {
        return [
            self::YOUTUBE,
            self::HOTSPOT,
            self::SURVEY,
        ];
    }

    /**
     * @param $mimeType
     * @return string
     */
    static public function getSubtypeByMimeType($mimeType) : string
    {
        switch ($mimeType) {
            case in_array($mimeType, FileMimeTypes::getAllImages()):
                return self::IMAGE;
                break;
            case in_array($mimeType, FileMimeTypes::getAllVideos()):
                return self::VIDEO;
                break;
            default:
                throw new \Exception('This format is not supported, please try another one.');
        }
    }
}
