<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBloodBagRequest extends FormRequest
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
        $bloodBag = $this->route('blood_bag');

        return [
            'refrigerator_id' => ['sometimes', 'exists:refrigerators,id'],
            'bag_number' => [
                'sometimes',
                'string',
                'max:255',
                Rule::unique('blood_bags', 'bag_number')->ignore($bloodBag?->id),
            ],
            'blood_group' => ['sometimes', 'string', 'max:5'],
            'donor_name' => ['sometimes', 'string', 'max:255'],
            'collection_date' => ['sometimes', 'date'],
            'expiry_date' => ['sometimes', 'date'],
            'quantity_ml' => ['sometimes', 'integer', 'min:1'],
            'status' => ['sometimes', 'in:available,reserved,dispatched,expired'],
        ];
    }
}
