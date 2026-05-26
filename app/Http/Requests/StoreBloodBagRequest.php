<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBloodBagRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return in_array($this->user()?->role, ['admin', 'staff'], true);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'refrigerator_id' => ['required', 'exists:refrigerators,id'],
            'bag_number' => ['required', 'string', 'max:255', 'unique:blood_bags,bag_number'],
            'blood_group' => ['required', 'string', 'max:5'],
            'donor_name' => ['required', 'string', 'max:255'],
            'collection_date' => ['required', 'date'],
            'expiry_date' => ['required', 'date', 'after:collection_date'],
            'quantity_ml' => ['required', 'integer', 'min:1'],
            'status' => ['required', 'in:available,reserved,dispatched,expired'],
        ];
    }
}
