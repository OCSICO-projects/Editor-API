<?php

namespace App\Enums;

class FileMimeTypes
{
    /**
     * @return array
     */
    static public function getAllImages() : array
    {
        return [
            'jpg'   => 'image/jpeg',
            'jpeg'  => 'image/jpeg',
            'jpe'   => 'image/jpeg',
            'png'   => 'image/png',
            'gif'   => 'image/gif',
            'bmp'   => 'image/bmp',
            'tiff'  => 'image/tiff',
            'tif'   => 'image/tiff',
            'svg'   => 'image/svg+xml',
        ];
    }

    /**
     * @return array
     */
    static public function getAllVideos() : array
    {
        return [
            'mpeg'  => 'video/mpeg',
            'mpg'   => 'video/mpeg',
            'mpe'   => 'video/mpeg',
            'qt'    => 'video/quicktime',
            'mov'   => 'video/quicktime',
            'avi'   => 'video/x-msvideo',
            'movie' => 'video/x-sgi-movie',
            'rv'    => 'video/vnd.rn-realvideo',
            'f4v'   => 'video/mp4',
            'webm'  => 'video/webm',
            'wmv'   => 'video/x-ms-wmv',
            'xspf'  => 'application/xspf+xml',
            'vlc'   => 'application/videolan',
        ];
    }
}
