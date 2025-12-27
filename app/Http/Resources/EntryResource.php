<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EntryResource extends JsonResource
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
            'owner' => [
                'user_id' => $this->owner->user_id,
                'full_name' => $this->owner->full_name,
                'wing_name' => $this->owner->wing_name,
                'flat_no' => $this->owner->flat_no,
            ],
            'staff' => [
                'user_id' => $this->staff->user_id,
                'full_name' => $this->staff->full_name,
            ],
            'entry_mode' => $this->entry_mode,
            'entry_type' => $this->entry_type,
            'vehicle_number' => $this->vehicle_number,
            'notes' => $this->notes,
            'entry_date' => $this->entry_date->format('Y-m-d'),
            'entry_time' => $this->entry_time->format('H:i'),
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
