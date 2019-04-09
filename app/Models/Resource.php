<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Resource extends Model
{
    public $thumbnail;

    protected $fillable = [
        'user_id',
        'folder_id',
        'file_id',
        'preview_id',
        'version',
        'name',
        'type',
        'subtype',
        'content',
        'url',
        'thumbnail',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function file()
    {
        return $this->hasOne(File::class, 'id', 'file_id');
    }

    public function preview()
    {
        return $this->hasOne(File::class, 'id', 'preview_id');
    }

    public function children()
    {
        return $this->belongsToMany(self::class, 'resource_relations', 'resource_parent_id', 'resource_id')
            ->withTimestamps();
    }

    public function parents()
    {
        return $this->belongsToMany(self::class, 'resource_relations', 'resource_id', 'resource_parent_id')
            ->withTimestamps();
    }

    public function folder()
    {
        return $this->hasOne(Folder::class, 'id', 'folder_id');
    }

    /**
     * @param $value
     */
    public function setThumbnailAttribute($value)
    {
        $this->thumbnail = $value;
    }
}
