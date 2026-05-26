<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BloodBagResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'bag_number' => $this->bag_number,
            'blood_group' => $this->blood_group,
            'donor_name' => $this->donor_name,
            'collection_date' => $this->collection_date?->toDateString(),
            'expiry_date' => $this->expiry_date?->toDateString(),
            'days_until_expiry' => $this->days_until_expiry,
            'is_expired' => $this->is_expired,
            'quantity_ml' => $this->quantity_ml,
            'status' => $this->status,
            'refrigerator' => [
                'id' => $this->whenLoaded('refrigerator', fn () => $this->refrigerator->id),
                'name' => $this->whenLoaded('refrigerator', fn () => $this->refrigerator->name),
                'code' => $this->whenLoaded('refrigerator', fn () => $this->refrigerator->code),
                'blood_bank' => $this->whenLoaded('refrigerator', fn () => [
                    'id' => $this->refrigerator->bloodBank?->id,
                    'name' => $this->refrigerator->bloodBank?->name,
                    'code' => $this->refrigerator->bloodBank?->code,
        ]),
    ],
    'created_at' => $this->created_at?->toDateTimeString(),
    ];
    }
}
