<?php

namespace App\Http\Requests\Resource;

use App\Models\Folder;
use App\Models\Resource;
use Illuminate\Foundation\Http\FormRequest;

class GetResourceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $resource = $this->route('resource');

        return $resource && $this->user()->can('access', $resource);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [];
    }
}
