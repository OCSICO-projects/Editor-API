<?php

namespace App\Models;

class Folder extends BasicModel
{
    protected $fillable = [
        'name',
        'parent_id'
    ];

    public function children()
    {
        return $this->hasMany(Folder::class, 'parent_id', 'id');
    }

    public function parent()
    {
        return $this->belongsTo(Folder::class, 'parent_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function resources()
    {
        return $this->hasMany(Resource::class, 'folder_id', 'id');
    }
}
