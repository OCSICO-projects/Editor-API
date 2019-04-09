<?php

namespace App\Enums;

class SocialiteProviders
{
    CONST FACEBOOK = 'facebook';
    CONST GOOGLE = 'google';

    /**
     * @return array
     */
    static public function getAll() : array
    {
        $class = new \ReflectionClass(__CLASS__);

        return $class->getConstants();
    }
}
