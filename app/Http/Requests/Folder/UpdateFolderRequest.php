<?php

namespace App\Http\Requests\Folder;

use App\Models\Folder;
use Illuminate\Foundation\Http\FormRequest;

class UpdateFolderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $folder = $this->route('folder');
        $parent = Folder::find($this->post('parent_id'));

        if (!$folder) {
            return true;
        }

        return $folder && $this->user()->can('update', [$folder, $parent]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'max:255|min:1',
            'folder' => 'required|exists:folders,id',
            'parent_id' => 'integer|different:folder|nullable'
        ];
    }

    /**
     * @param null $keys
     * @return array
     */
    public function all($keys = null)
    {
        $data = parent::all($keys);
        $data['folder'] = $this->route('folder')->id;

        return $data;
    }
}
