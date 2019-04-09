<?php

namespace App\Models;

class File extends BasicModel
{
    protected $fillable = [
        'user_id',
        'original_id',
        'disk',
        'path',
        'directory',
        'url',
        'filename',
        'original_filename',
        'extension',
        'media_type',
        'size',
        'duration'
    ];

    /**
     * @return string
     */
    public function getUrlAttribute()
    {
        return config('app.url') . '/files/data/' . $this->id;
    }

    /**
     * @return string
     */
    public function preview()
    {
        return $this->hasOne(self::class, 'original_id', 'id');
    }

    /**
     * @return string
     */
    public function original()
    {
        return $this->belongsTo(self::class, 'id', 'original_id');
    }
}
