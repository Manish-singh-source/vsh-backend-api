<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EquipmentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'image' => $this->image ? asset('storage/equipment/' . $this->image) : null,
            'description' => $this->description,
            'wing_name' => $this->wing_name,
            'is_bookable' => (bool) $this->is_bookable,
            'status' => $this->status,
            'added_by' => $this->added_by,
            'added_at' => $this->added_at?->toDateTimeString(),
            'added_by_user' => new UserResource($this->whenLoaded('user')),
            'created_at' => $this->created_at?->toDateTimeString(),
        ];
    }
}
