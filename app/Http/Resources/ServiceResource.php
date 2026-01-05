<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'service_category' => $this->service_category,
            'phone' => $this->phone,
            'opening_hours' => $this->opening_hours,
            'address' => $this->address,
            'status' => $this->status,
            'added_by' => $this->added_by,
            'added_at' => $this->added_at?->toDateTimeString(),
            'added_by_user' => new UserResource($this->whenLoaded('user')),
            'created_at' => $this->created_at?->toDateTimeString(),
        ];
    }
}
