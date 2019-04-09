<?php

namespace App\Http\Services;

use App\Models\Folder;
use Illuminate\Support\Facades\Auth;

class FolderService
{
    /**
     * @param $data
     * @return Folder
     */
    public function create($data)
    {
        $folder = new Folder($data->all());
        $folder->user_id = Auth::user()->id;
        $folder->save();

        return $folder;
    }

    /**
     * @param Folder $folder
     * @param $data
     * @return mixed
     */
    public function update(Folder $folder, $data)
    {
        if (isset($data['parent_id']) && $data['parent_id']) {                           //Recursion handler
            Folder::where('parent_id', $folder->id)->update(['parent_id' => null]);
        }

        return $folder->update($data);
    }

    /**
     * @param int $id
     * @return int
     */
    public function delete(int $id)
    {
        $folder = Folder::find($id);

        if ($folder) {
            return (int)$folder->delete();
        } else {
            return 0;
        }
    }
}
