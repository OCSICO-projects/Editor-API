<?php

namespace App\Enums;

class ResourceTypes
{
    CONST COMPOSE = 'compose';
    CONST FILE = 'file';

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
    static public function getAllFileSybTypes() : array
    {
        return [
            ResourceSubtypes::IMAGE,
            ResourceSubtypes::PDF,
            ResourceSubtypes::VIDEO,
            ResourceSubtypes::YOUTUBE,
        ];
    }

    /**
     * @return array
     */
    static public function getAllComposeSybTypes() : array
    {
        return [
            ResourceSubtypes::SLIDE,
            ResourceSubtypes::HOTSPOT,
            ResourceSubtypes::SURVEY,
        ];
    }
}
