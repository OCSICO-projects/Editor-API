<?php

namespace App\Http\Requests\Resource;

use App\Enums\ResourceSubtypes;
use App\Enums\ResourceTypes;
use App\Models\File;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateResourceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $file = $this->post('file_id') ? File::find($this->post('file_id')) : null;

        return $file ? $this->user()->can('access', $file) : true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'folder_id' => 'integer|exists:folders,id|nullable',
            'file_id' => 'integer|exists:files,id|nullable',
            'preview_id' => 'integer|nullable',
            'version' => 'integer|nullable',
            'name' => 'required|max:255',
            'type' => [
                'required',
                'max:255',
                Rule::in(ResourceTypes::getAll())
            ],
            'subtype' => [
                'required',
                'max:255',
                Rule::in(ResourceSubtypes::getAllNotUploadable()),
            ],
            'content' => 'json|nullable',
            'url' => 'url|nullable|required_if:subtype,' . ResourceSubtypes::YOUTUBE,
            'thumbnail' => 'nullable|required_if:subtype,' . ResourceSubtypes::SLIDE,
            'relates' => 'array|required_if:file_id,null',
            'relates.*' => 'distinct|exists:resources,id'
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'relates.*.exists' => 'Related resource with id = :input doesn\'t exist',
        ];
    }
}
