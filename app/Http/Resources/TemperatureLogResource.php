<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TemperatureLogResource extends JsonResource
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
            'temperature' => $this->temperature,
            'status' => $this->status,
            'recorded_at' => $this->recorded_at?->toDateTimeString(),
            'refrigerator' => $this->whenLoaded('refrigerator', fn () => [
                'id' => $this->refrigerator->id,
                'name' => $this->refrigerator->name,
                'code' => $this->refrigerator->code,
            ]),
        ];
    }
}
