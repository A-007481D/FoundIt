<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title'        => 'sometimes|required|string|max:255',
            'description'  => 'sometimes|required|string',
            'location'     => 'sometimes|required|string|max:255',
            'category_id'  => 'sometimes|required|exists:categories,id',
            'image'        => 'nullable|image|max:2048',
            'lost_date'    => 'sometimes|required_if:type,lost|date',
            'found_date'   => 'sometimes|required_if:type,found|date',
        ];
    }
}
