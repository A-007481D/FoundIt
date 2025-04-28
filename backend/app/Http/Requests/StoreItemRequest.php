<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title'        => 'required|string|max:255',
            'description'  => 'required|string',
            'type'         => 'required|in:lost,found',
            'location'     => 'required|string|max:255',
            'category_id'  => 'required|exists:categories,id',
            'image'        => 'nullable|image|max:2048',
            'lost_date'    => 'required_if:type,lost|date',
            'found_date'   => 'required_if:type,found|date',
        ];
    }
}
