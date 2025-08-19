<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLocationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:locations,slug' . ($this->location_id ? ',' . $this->location_id : ''),
            'is_active' => 'required|boolean',
            'location_id' => 'nullable|exists:locations,id',
            'location_type' => 'required|in:town,workshop',
        ];
    }
}
