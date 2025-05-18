<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SeasonResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'status' => $this->status,
            'starts_at' => $this->starts_at,
            'end_date' => $this->end_date,
            'time_limit_hours' => $this->time_limit_hours,
            'is_locked' => $this->is_locked,
            'winner_team_id' => $this->winner_team_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
