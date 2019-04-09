<?php

namespace App\Models;

class ResourceRelation extends BasicModel
{
    public function child()
    {
        return $this->belongsTo(Resource::class, 'resource_id');
    }

    public function parent()
    {
        return $this->belongsTo(Resource::class, 'resource_parent_id');
    }
}
