<?php

namespace App\Http\Requests\Folder;

use App\Models\Folder;
use Illuminate\Foundation\Http\FormRequest;

class DeleteFolderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $folder = Folder::find($this->route('id'));

        if (!$folder) {
            return true;
        }

        return $folder && $this->user()->can('access', $folder);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'id' => 'required|exists:folders,id'
        ];
    }

    /**
     * @param null $keys
     * @return array
     */
    public function all($keys = null)
    {
        $data = parent::all($keys);
        $data['id'] = (int)$this->route('id');

        return $data;
    }
}
