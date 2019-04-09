<?php

namespace App\Http\Requests;

use App\Enums\ResourceSubtypes;
use App\Enums\ResourceTypes;
use App\Models\File;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateResourceRequest extends FormRequest
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
            'name' => 'max:255',
            'type' => [
                'max:255',
                Rule::in(ResourceTypes::getAll())
            ],
            'subtype' => [
                'max:255',
                Rule::in(ResourceSubtypes::getAll()),
            ],
            'content' => 'json|nullable',
            'url' => 'url|nullable',
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
